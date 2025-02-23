<?php

namespace App\Http\Livewire;

use App\Models\Project;
use App\Repositories\ProjectRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Projects extends SearchableComponent
{
    public $statusFilter = '';

    public $billingType = '';

    public $customer = '';

    /**
     * @return Application|Factory
     */
    public function render()
    {
        $projects = $this->searchProjects();
        $projectRepo = app(ProjectRepository::class);
        $data['statusCount'] = $projectRepo->getProjectsStatusCount($this->customer);
        $data['search'] = '';
        $data['customer'] = $this->customer;
        $data['projectStatusArr'] = Project::STATUS;

        return view('livewire.projects', [
            'projects' => $projects, ])->with($data);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function searchProjects()
    {
        $this->setQuery($this->getQuery()->with(['customer', 'members.user.media', 'projectContacts', 'tags']));

        $this->getQuery()->where(function (Builder $query) {
            $this->filterResults();
        });

        $this->getQuery()->when($this->statusFilter !== '', function (Builder $q) {
            $q->where('status', $this->statusFilter);
        });

        $this->getQuery()->when($this->billingType !== '', function (Builder $q) {
            $q->where('billing_type', $this->billingType);
        });

        $this->getQuery()->when($this->customer !== '', function (Builder $q) {
            $q->where('customer_id', $this->customer);
        });

        return $this->paginate();
    }

    /**
     * @param $projectId
     */
    public function deleteProject($projectId)
    {
        $project = Project::find($projectId);
        activity()->performedOn($project)->causedBy(getLoggedInUser())
            ->useLog('Project deleted.')->log($project->project_name.' Project deleted.');
        $project->delete();
        $project->members()->delete();
        $this->dispatchBrowserEvent('deleted');
        $this->searchProjects();
    }

    public function filterProjectsByStatus($projectId)
    {
        $this->statusFilter = $projectId;
        $this->resetPage();
    }

    public function filterProjectsByBillingType($projectId)
    {
        $this->billingType = $projectId;
        $this->resetPage();
    }

    /**
     * @var string[]
     */
    protected $listeners = [
        'refresh' => '$refresh',
        'deleteProject',
        'filterProjectsByStatus',
        'filterProjectsByBillingType',
    ];

    /**
     * @return string
     */
    public function model()
    {
        return Project::class;
    }

    /**
     * @return string[]
     */
    public function searchableFields()
    {
        return [
            'project_name',
            'customer.company_name',
        ];
    }

    /**
     * @return Builder
     */
    public function filterResults()
    {
        $searchableFields = $this->searchableFields();
        $search = $this->search;

        $this->getQuery()->when(! empty($search), function (Builder $q) use ($search, $searchableFields) {
            $this->getQuery()->where(function (Builder $q) use ($search, $searchableFields) {
                $searchString = '%'.$search.'%';
                foreach ($searchableFields as $field) {
                    if (Str::contains($field, '.')) {
                        $field = explode('.', $field);
                        $q->orWhereHas($field[0], function (Builder $query) use ($field, $searchString) {
                            $query->whereRaw("lower($field[1]) like ?", $searchString);
                        });
                    } else {
                        $q->orWhereRaw("lower($field) like ?", $searchString);
                    }
                }
            });
        });

        return $this->getQuery();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
