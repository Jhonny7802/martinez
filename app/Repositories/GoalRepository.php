<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Models\Goal;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Class GoalRepository
 *
 * @version April 21, 2020, 8:30 am UTC
 */
class GoalRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'subject',
        'user_id',
        'goal_type_id',
        'description',
        'start_time',
        'end_date',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Goal::class;
    }

    /**
     * @param  array  $input
     */
    public function store($input)
    {
        $goalInputs = Arr::except($input, ['users']);
        $goalInputs['is_notify'] = (isset($goalInputs['is_notify']) && ! empty($goalInputs['is_notify'])) ? 1 : 0;
        $goalInputs['is_not_notify'] = (isset($goalInputs['is_not_notify']) && ! empty($goalInputs['is_not_notify'])) ? 1 : 0;

        /** @var Goal $goal */
        $goal = $this->create($goalInputs);
        $userIds = $input['users'];
        $users = User::whereIn('id', $userIds)->get();

        if (! empty($input['users'])) {
            foreach ($users as $user) {
                Notification::create([
                    'title' => 'New Goal Created',
                    'description' => 'You are assigned to '.$goal->subject,
                    'type' => Goal::class,
                    'user_id' => $user->id,
                ]);
            }
        }

        activity()->performedOn($goal)->causedBy(getLoggedInUser())
            ->useLog('New Goal created.')->log($goal->subject.' Goal created.');

        if (isset($input['users']) && ! empty($input['users'])) {
            $goal->goalMembers()->sync($input['users']);
        }
    }

    /**
     * @param  int  $id
     * @param  array  $input
     * @return Goal
     */
    public function updateGoal($id, $input)
    {
        $goalInputs = Arr::except($input, ['users']);
        $goalInputs['is_notify'] = (isset($goalInputs['is_notify']) && ! empty($goalInputs['is_notify'])) ? 1 : 0;
        $goalInputs['is_not_notify'] = (isset($goalInputs['is_not_notify']) && ! empty($goalInputs['is_not_notify'])) ? 1 : 0;

        $goal = Goal::findOrFail($id);
        $oldUserIds = $goal->goalMembers->pluck('id')->toArray();
        $newUserIds = $input['users'];
        $removedUserIds = array_diff($oldUserIds, $newUserIds);
        $userIds = array_diff($newUserIds, $oldUserIds);
        $users = User::whereId($userIds)->get();
        $goal = $this->update($goalInputs, $id);

        if (! empty($removedUserIds)) {
            foreach ($removedUserIds as $removedUser) {
                Notification::create([
                    'title' => 'Removed From Goal',
                    'description' => 'You removed from '.$goal->subject,
                    'type' => Goal::class,
                    'user_id' => $removedUser,
                ]);
            }
        }
        if ($users->count() > 0) {
            foreach ($users as $user) {
                Notification::create([
                    'title' => 'New Goal Assigned',
                    'description' => 'You are assigned to '.$goal->subject,
                    'type' => Goal::class,
                    'user_id' => $user->id,
                ]);
                foreach ($oldUserIds as $oldUser) {
                    Notification::create([
                        'title' => 'New User Assigned to Goal',
                        'description' => $user->first_name.' '.$user->last_name.' assigned to '.$goal->subject,
                        'type' => Goal::class,
                        'user_id' => $oldUser,
                    ]);
                }
            }
        }

        activity()->performedOn($goal)->causedBy(getLoggedInUser())
            ->useLog('Goal updated.')->log($goal->subject.' Goal updated.');

        if (isset($input['users']) && ! empty($input['users'])) {
            $goal->goalMembers()->sync($input['users']);
        }

        return $goal;
    }

    /**
     * @param  int  $id
     * @return mixed
     */
    public function getGoalDetails($id)
    {
        $goal = Goal::with(['goalMembers'])->find($id);

        return $goal;
    }

    /**
     * @return Collection
     */
    public function getStaffMember()
    {
        /** @var User $user */
        return User::orderBy('first_name')->whereIsEnable(true)->user()->get()->pluck('full_name', 'id')->toArray();
    }

    /**
     * MÉTODO CORREGIDO - Calcula el progreso del objetivo
     * 
     * @param array $data
     * @return float|int
     */
    public function countGoalProgress($data)
    {
        $startDate = $data['start_date'];
        $endDate = $data['end_date'];
        $achievement = (float) $data['achievement'];
        $goalMembers = $data['goal_members'] ?? [];

        // Validaciones básicas
        if ($achievement <= 0) {
            return 0;
        }

        try {
            // CORREGIDO: goal_type en lugar de goal_ype
            if ($data['goal_type'] == Goal::INVOICE_AMOUNT) {
                return $this->calculateInvoiceGoalProgress($startDate, $endDate, $achievement, $goalMembers);
                
            } elseif ($data['goal_type'] == Goal::CONVERT_X_LEAD) {
                return $this->calculateLeadConversionProgress($startDate, $endDate, $achievement, $goalMembers);
                
            } elseif ($data['goal_type'] == Goal::INCREASE_CUSTOMER_NUMBER) {
                return $this->calculateCustomerGrowthProgress($startDate, $endDate, $achievement, $goalMembers);
            }

            return 0;
            
        } catch (\Exception $e) {
            \Log::error('Error calculating goal progress: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Calcular progreso para objetivos de facturación
     */
    private function calculateInvoiceGoalProgress($startDate, $endDate, $achievement, $goalMembers)
    {
        $query = Invoice::wherePaymentStatus(Invoice::STATUS_PAID)
            ->whereBetween('invoice_date', [$startDate, $endDate]);

        // Si hay miembros específicos del objetivo, filtrar por ellos
        if (!empty($goalMembers)) {
            $query->whereIn('sales_agent_id', $goalMembers);
        }

        $currentAmount = $query->sum('total_amount');

        // Calcular porcentaje progresivo (no binario)
        $percentage = ($currentAmount / $achievement) * 100;
        
        // Devolver número decimal, limitado a 100%
        return min(round($percentage, 2), 100);
    }

    /**
     * Calcular progreso para conversión de leads
     */
    private function calculateLeadConversionProgress($startDate, $endDate, $achievement, $goalMembers)
    {
        $query = Lead::whereLeadConvertCustomer(true)
            ->whereBetween('lead_convert_date', [$startDate, $endDate]);

        if (!empty($goalMembers)) {
            $query->whereIn('sales_agent_id', $goalMembers);
        }

        $convertedLeads = $query->count();

        $percentage = ($convertedLeads / $achievement) * 100;
        return min(round($percentage, 2), 100);
    }

    /**
     * Calcular progreso para crecimiento de clientes
     */
    private function calculateCustomerGrowthProgress($startDate, $endDate, $achievement, $goalMembers)
    {
        $query = Customer::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if (!empty($goalMembers)) {
            $query->whereIn('sales_agent_id', $goalMembers);
        }

        $newCustomers = $query->count();

        $percentage = ($newCustomers / $achievement) * 100;
        return min(round($percentage, 2), 100);
    }

}
