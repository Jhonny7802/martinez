<?php

namespace App\Http\Controllers;

use App\Models\DesignProject;
use App\Models\DesignTemplate;
use App\Models\DesignElement;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class DesignGenerationController extends AppBaseController
{
    /**
     * Display a listing of design projects.
     */
    public function index(Request $request)
    {
        $query = DesignProject::with(['customer', 'template'])->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by customer
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Search by project name or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('project_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $designProjects = $query->paginate(15);
        $customers = Customer::select('id', 'company_name')->orderBy('company_name')->get();

        return view('design_generation.index', compact('designProjects', 'customers'));
    }

    /**
     * Show the form for creating a new design project.
     */
    public function create()
    {
        $customers = Customer::select('id', 'company_name')
                           ->orderBy('company_name')
                           ->get();
        
        $templates = DesignTemplate::where('is_active', true)
                                 ->orderBy('name')
                                 ->get();

        return view('design_generation.create', compact('customers', 'templates'));
    }

    /**
     * Store a newly created design project.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_name' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'template_id' => 'nullable|exists:design_templates,id',
            'description' => 'nullable|string',
            'dimensions' => 'required|string|max:100',
            'color_scheme' => 'required|string|max:100',
            'design_elements' => 'nullable|array',
            'design_elements.*.type' => 'required_with:design_elements|in:text,image,shape,logo',
            'design_elements.*.content' => 'required_with:design_elements|string',
            'design_elements.*.position_x' => 'required_with:design_elements|numeric',
            'design_elements.*.position_y' => 'required_with:design_elements|numeric',
            'design_elements.*.width' => 'required_with:design_elements|numeric|min:1',
            'design_elements.*.height' => 'required_with:design_elements|numeric|min:1',
            'design_elements.*.style_properties' => 'nullable|json',
            'deadline' => 'nullable|date|after:today',
            'budget' => 'nullable|numeric|min:0',
            'priority' => 'required|in:low,medium,high,urgent'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create the design project
            $designProject = DesignProject::create([
                'project_name' => $request->project_name,
                'customer_id' => $request->customer_id,
                'template_id' => $request->template_id,
                'description' => $request->description,
                'dimensions' => $request->dimensions,
                'color_scheme' => $request->color_scheme,
                'deadline' => $request->deadline,
                'budget' => $request->budget ?? 0,
                'priority' => $request->priority,
                'status' => 'draft',
                'created_by' => auth()->id()
            ]);

            // Create design elements (if provided) or generate default house design
            if ($request->design_elements && count($request->design_elements) > 0) {
                foreach ($request->design_elements as $elementData) {
                    DesignElement::create([
                        'design_project_id' => $designProject->id,
                        'element_type' => $elementData['type'],
                        'content' => $elementData['content'],
                        'position_x' => $elementData['position_x'],
                        'position_y' => $elementData['position_y'],
                        'width' => $elementData['width'],
                        'height' => $elementData['height'],
                        'style_properties' => $elementData['style_properties'] ?? null,
                        'layer_order' => $elementData['layer_order'] ?? 1
                    ]);
                }
            } else {
                // Generate default house design elements
                $this->generateHouseDesignElements($designProject);
            }

            DB::commit();

            // Generate preview automatically
            $this->generatePreview($designProject);

            return redirect()->route('design-generation.show', $designProject)
                           ->with('success', 'Proyecto de diseño creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Error al crear el proyecto: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Generate default house design elements
     */
    private function generateHouseDesignElements(DesignProject $designProject)
    {
        $dimensions = explode('x', $designProject->dimensions);
        $width = (int)($dimensions[0] ?? 1920);
        $height = (int)($dimensions[1] ?? 1080);

        $elements = [
            // House structure
            [
                'element_type' => 'shape',
                'content' => 'Casa Principal',
                'position_x' => $width * 0.2,
                'position_y' => $height * 0.3,
                'width' => $width * 0.6,
                'height' => $height * 0.5,
                'style_properties' => json_encode([
                    'background_color' => '#f5f5dc',
                    'border_color' => '#8b4513',
                    'border_width' => '3px'
                ]),
                'layer_order' => 1
            ],
            // Roof
            [
                'element_type' => 'shape',
                'content' => 'Techo',
                'position_x' => $width * 0.15,
                'position_y' => $height * 0.15,
                'width' => $width * 0.7,
                'height' => $height * 0.2,
                'style_properties' => json_encode([
                    'background_color' => '#8b0000',
                    'border_color' => '#654321'
                ]),
                'layer_order' => 2
            ],
            // Front door
            [
                'element_type' => 'shape',
                'content' => 'Puerta Principal',
                'position_x' => $width * 0.45,
                'position_y' => $height * 0.6,
                'width' => $width * 0.1,
                'height' => $height * 0.2,
                'style_properties' => json_encode([
                    'background_color' => '#654321',
                    'border_color' => '#000000'
                ]),
                'layer_order' => 3
            ],
            // Windows
            [
                'element_type' => 'shape',
                'content' => 'Ventana Izquierda',
                'position_x' => $width * 0.25,
                'position_y' => $height * 0.4,
                'width' => $width * 0.12,
                'height' => $height * 0.15,
                'style_properties' => json_encode([
                    'background_color' => '#87ceeb',
                    'border_color' => '#000000'
                ]),
                'layer_order' => 3
            ],
            [
                'element_type' => 'shape',
                'content' => 'Ventana Derecha',
                'position_x' => $width * 0.63,
                'position_y' => $height * 0.4,
                'width' => $width * 0.12,
                'height' => $height * 0.15,
                'style_properties' => json_encode([
                    'background_color' => '#87ceeb',
                    'border_color' => '#000000'
                ]),
                'layer_order' => 3
            ],
            // Garage
            [
                'element_type' => 'shape',
                'content' => 'Garaje',
                'position_x' => $width * 0.05,
                'position_y' => $height * 0.4,
                'width' => $width * 0.2,
                'height' => $height * 0.4,
                'style_properties' => json_encode([
                    'background_color' => '#dcdcdc',
                    'border_color' => '#696969'
                ]),
                'layer_order' => 1
            ],
            // Garden/Landscaping
            [
                'element_type' => 'shape',
                'content' => 'Jardín Frontal',
                'position_x' => $width * 0.1,
                'position_y' => $height * 0.85,
                'width' => $width * 0.8,
                'height' => $height * 0.1,
                'style_properties' => json_encode([
                    'background_color' => '#228b22',
                    'border_color' => '#006400'
                ]),
                'layer_order' => 0
            ],
            // Project title
            [
                'element_type' => 'text',
                'content' => $designProject->project_name,
                'position_x' => $width * 0.1,
                'position_y' => $height * 0.05,
                'width' => $width * 0.8,
                'height' => $height * 0.08,
                'style_properties' => json_encode([
                    'font_size' => '24px',
                    'font_weight' => 'bold',
                    'color' => '#2c3e50',
                    'text_align' => 'center'
                ]),
                'layer_order' => 10
            ]
        ];

        foreach ($elements as $elementData) {
            DesignElement::create(array_merge($elementData, [
                'design_project_id' => $designProject->id,
                'is_visible' => true,
                'is_locked' => false
            ]));
        }
    }

    /**
     * Display the specified design project.
     */
    public function show(DesignProject $designProject)
    {
        $designProject->load(['customer', 'template', 'elements', 'createdBy']);
        return view('design_generation.show', compact('designProject'));
    }

    /**
     * Show the form for editing the specified design project.
     */
    public function edit(DesignProject $designProject)
    {
        if ($designProject->status === 'completed') {
            return redirect()->route('design-generation.show', $designProject)
                           ->with('error', 'No se puede editar un proyecto completado.');
        }

        $customers = Customer::select('id', 'company_name')
                           ->orderBy('company_name')
                           ->get();
        
        $templates = DesignTemplate::where('is_active', true)
                                 ->orderBy('name')
                                 ->get();

        $designProject->load('elements');

        return view('design_generation.edit', compact('designProject', 'customers', 'templates'));
    }

    /**
     * Update the specified design project.
     */
    public function update(Request $request, DesignProject $designProject)
    {
        if ($designProject->status === 'completed') {
            return redirect()->route('design-generation.show', $designProject)
                           ->with('error', 'No se puede editar un proyecto completado.');
        }

        $validator = Validator::make($request->all(), [
            'project_name' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'template_id' => 'nullable|exists:design_templates,id',
            'description' => 'nullable|string',
            'dimensions' => 'required|string|max:100',
            'color_scheme' => 'required|string|max:100',
            'deadline' => 'nullable|date|after:today',
            'budget' => 'nullable|numeric|min:0',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:draft,in_progress,review,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            $designProject->update($request->all());

            return redirect()->route('design-generation.show', $designProject)
                           ->with('success', 'Proyecto actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al actualizar el proyecto: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified design project.
     */
    public function destroy(DesignProject $designProject)
    {
        if ($designProject->status === 'completed') {
            return redirect()->route('design-generation.index')
                           ->with('error', 'No se puede eliminar un proyecto completado.');
        }

        try {
            // Delete associated files if any
            if ($designProject->preview_image) {
                Storage::delete($designProject->preview_image);
            }
            if ($designProject->final_design) {
                Storage::delete($designProject->final_design);
            }

            $designProject->delete();
            
            return redirect()->route('design-generation.index')
                           ->with('success', 'Proyecto eliminado exitosamente.');
                           
        } catch (\Exception $e) {
            return redirect()->route('design-generation.index')
                           ->with('error', 'Error al eliminar el proyecto: ' . $e->getMessage());
        }
    }

    /**
     * Generate design preview
     */
    public function generatePreview(DesignProject $designProject)
    {
        try {
            // Generate SVG preview of the house design
            $this->createSVGPreview($designProject);
            
            $designProject->update([
                'status' => 'in_progress',
                'preview_generated_at' => now()
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vista previa generada exitosamente.',
                    'preview_url' => route('design-generation.preview', $designProject)
                ]);
            }

            return $designProject;

        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'error' => 'Error al generar la vista previa: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    /**
     * Create SVG preview of the design
     */
    private function createSVGPreview(DesignProject $designProject)
    {
        $dimensions = explode('x', $designProject->dimensions);
        $width = (int)($dimensions[0] ?? 800);
        $height = (int)($dimensions[1] ?? 600);
        
        $elements = $designProject->elements()->where('is_visible', true)->orderBy('layer_order')->get();
        
        $svg = '<?xml version="1.0" encoding="UTF-8"?>';
        $svg .= '<svg width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">';
        
        // Background
        $colors = explode(',', $designProject->color_scheme);
        $primaryColor = trim($colors[0] ?? '#ffffff');
        $secondaryColor = trim($colors[1] ?? '#f8f9fa');
        
        $svg .= '<defs>';
        $svg .= '<linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">';
        $svg .= '<stop offset="0%" style="stop-color:' . $primaryColor . ';stop-opacity:1" />';
        $svg .= '<stop offset="100%" style="stop-color:' . $secondaryColor . ';stop-opacity:1" />';
        $svg .= '</linearGradient>';
        $svg .= '</defs>';
        
        $svg .= '<rect width="100%" height="100%" fill="url(#bg)" />';
        
        // Render elements
        foreach ($elements as $element) {
            $style = json_decode($element->style_properties, true) ?? [];
            
            if ($element->element_type === 'shape') {
                $fillColor = $style['background_color'] ?? '#cccccc';
                $strokeColor = $style['border_color'] ?? '#000000';
                $strokeWidth = $style['border_width'] ?? '1px';
                
                $svg .= '<rect x="' . $element->position_x . '" y="' . $element->position_y . '" ';
                $svg .= 'width="' . $element->width . '" height="' . $element->height . '" ';
                $svg .= 'fill="' . $fillColor . '" stroke="' . $strokeColor . '" ';
                $svg .= 'stroke-width="' . str_replace('px', '', $strokeWidth) . '" />';
                
            } elseif ($element->element_type === 'text') {
                $fontSize = $style['font_size'] ?? '16px';
                $color = $style['color'] ?? '#000000';
                $fontWeight = $style['font_weight'] ?? 'normal';
                
                $svg .= '<text x="' . ($element->position_x + $element->width/2) . '" ';
                $svg .= 'y="' . ($element->position_y + $element->height/2) . '" ';
                $svg .= 'font-size="' . str_replace('px', '', $fontSize) . '" ';
                $svg .= 'font-weight="' . $fontWeight . '" ';
                $svg .= 'fill="' . $color . '" text-anchor="middle" dominant-baseline="middle">';
                $svg .= htmlspecialchars($element->content);
                $svg .= '</text>';
            }
        }
        
        $svg .= '</svg>';
        
        // Save SVG file
        $filename = 'design_previews/design_' . $designProject->id . '_' . time() . '.svg';
        Storage::disk('public')->put($filename, $svg);
        
        // Update project with preview image path
        $designProject->update(['preview_image' => $filename]);
    }

    /**
     * Show design preview
     */
    public function preview(DesignProject $designProject)
    {
        $designProject->load('elements');
        return view('design_generation.preview', compact('designProject'));
    }

    /**
     * Export design as PDF or image
     */
    public function export(Request $request, DesignProject $designProject)
    {
        $format = $request->get('format', 'pdf');
        
        try {
            if ($format === 'pdf') {
                $pdf = app('dompdf.wrapper');
                $pdf->loadView('design_generation.export_pdf', compact('designProject'));
                return $pdf->download('diseño-' . $designProject->project_name . '.pdf');
            }
            
            // For image export, you would implement image generation logic here
            return response()->json(['error' => 'Formato no soportado'], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al exportar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get templates by category
     */
    public function getTemplatesByCategory(Request $request)
    {
        $category = $request->get('category');
        
        $templates = DesignTemplate::where('is_active', true)
                                 ->when($category, function($query, $category) {
                                     return $query->where('category', $category);
                                 })
                                 ->orderBy('name')
                                 ->get(['id', 'name', 'description', 'preview_image']);

        return response()->json($templates);
    }
}
