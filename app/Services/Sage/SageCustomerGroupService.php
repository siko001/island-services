<?php

namespace App\Services\Sage;

use App\Helpers\Notifications;
use App\Models\Customer\CustomerGroup;
use App\Pipelines\Sage\CustomerGroup\CheckCustomerGroupExists;
use App\Pipelines\Sage\CustomerGroup\FormatCustomerGroupDataForSage;
use App\Pipelines\Sage\CustomerGroup\SendCustomerGroupCreationRequest;
use App\Pipelines\Sage\CustomerGroup\ValidateCustomerGroupForSage;
use App\Pipelines\Sage\SageConnection;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Log;

class SageCustomerGroupService
{
    public function createInSage(CustomerGroup $customerGroup): void
    {
        try {
            Log::info("Creating customer group in Sage: " . $customerGroup->name);

            $context = app(Pipeline::class)
                ->send($customerGroup)
                ->through([
                    ValidateCustomerGroupForSage::class,
                    FormatCustomerGroupDataForSage::class,
                    SageConnection::class,
                    CheckCustomerGroupExists::class,
                    SendCustomerGroupCreationRequest::class
                ])
                ->thenReturn();

            Notifications::notifyAdmins(
                $customerGroup,
                ['customer_group' => $customerGroup->name],
                'created',
                "Customer Group {customer_group} created successfully in Sage",
                'user-group',
            );

            Log::info('Sage API createInSage called for Customer Group', ['customer_group_id' => $customerGroup->id, 'context' => $context]);

        } catch(\Exception $err) {
            // Handle exception
            Log::error('Error creating customer group in Sage: ' . $err->getMessage(), [
                'exception' => $err,
            ]);

            Notifications::notifyAdmins(
                $customerGroup,
                ['customerGroup' => $customerGroup->name],
                'created',
                "Customer Group: {customerGroup} failed to create in Sage",
                'exclamation-circle',
                "error",
            );
            throw $err;
        }
    }

    public function updateInSage(CustomerGroup $customerGroup): void
    {
        try {
            Log::info("Updating customer group in Sage: " . $customerGroup->name);

            $context = app(Pipeline::class)
                ->send($customerGroup)
                ->through([
                    ValidateCustomerGroupForSage::class,
                    FormatCustomerGroupDataForSage::class,
                    SageConnection::class,
                    CheckCustomerGroupExists::class,
                    SendCustomerGroupCreationRequest::class
                ])
                ->thenReturn();

            Notifications::notifyAdmins(
                $customerGroup,
                ['customer_group' => $customerGroup->name],
                'created',
                "Customer Group {customer_group} updated successfully in Sage",
                'user-group',
            );

            Log::info('Sage API updateInSage called for Customer Group', ['customer_group_id' => $customerGroup->id, 'context' => $context]);

        } catch(\Exception $err) {
            // Handle exception
            Log::error('Error updating customer group in Sage: ' . $err->getMessage(), [
                'exception' => $err,
            ]);

            Notifications::notifyAdmins(
                $customerGroup,
                ['customerGroup' => $customerGroup->name],
                'created',
                "Customer Group: {customerGroup} failed to update in Sage",
                'exclamation-circle',
                "error"
            );
            throw $err;
        }
    }
}
