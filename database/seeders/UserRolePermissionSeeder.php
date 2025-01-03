<?php

namespace  Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        Permission::create(['name' => 'view role', 'type' => 'Roles']);
        Permission::create(['name' => 'create role', 'type' => 'Roles']);
        Permission::create(['name' => 'update role', 'type' => 'Roles']);
        Permission::create(['name' => 'delete role', 'type' => 'Roles']);

        Permission::create(['name' => 'view permission', 'type' => 'Permission']);
        Permission::create(['name' => 'create permission', 'type' => 'Permission']);
        Permission::create(['name' => 'update permission', 'type' => 'Permission']);
        Permission::create(['name' => 'delete permission', 'type' => 'Permission']);

        Permission::create(['name' => 'view user', 'type' => 'User']);
        Permission::create(['name' => 'create user', 'type' => 'User']);
        Permission::create(['name' => 'update user', 'type' => 'User']);
        Permission::create(['name' => 'delete user', 'type' => 'User']);

        Permission::create(['name' => 'view location', 'type' => 'Company - Location']);
        Permission::create(['name' => 'create location', 'type' => 'Company - Location']);
        Permission::create(['name' => 'update location', 'type' => 'Company - Location']);
        Permission::create(['name' => 'delete location', 'type' => 'Company - Location']);
        
        Permission::create(['name' => 'view station', 'type' => 'Company - Station']);
        Permission::create(['name' => 'create station', 'type' => 'Company - Station']);
        Permission::create(['name' => 'update station', 'type' => 'Company - Station']);
        Permission::create(['name' => 'delete station', 'type' => 'Company - Station']);
        
        Permission::create(['name' => 'view attendance requests', 'type' => 'Attendance Requests']);
        Permission::create(['name' => 'create attendance requests', 'type' => 'Attendance Requests']);
        Permission::create(['name' => 'update attendance requests', 'type' => 'Attendance Requests']);
        Permission::create(['name' => 'delete attendance requests', 'type' => 'Attendance Requests']);
        
        Permission::create(['name' => 'view mass punch', 'type' => 'Attendance - Mass Punch']);
        Permission::create(['name' => 'create mass punch', 'type' => 'Attendance - Mass Punch']);
        Permission::create(['name' => 'update mass punch', 'type' => 'Attendance - Mass Punch']);
        Permission::create(['name' => 'delete mass punch', 'type' => 'Attendance - Mass Punch']);
        
        Permission::create(['name' => 'view punch', 'type' => 'Attendance - Punch']);
        Permission::create(['name' => 'create punch', 'type' => 'Attendance - Punch']);
        Permission::create(['name' => 'update punch', 'type' => 'Attendance - Punch']);
        Permission::create(['name' => 'delete punch', 'type' => 'Attendance - Punch']);
        
        Permission::create(['name' => 'view timesheet', 'type' => 'Attendance - Timesheet']);
        Permission::create(['name' => 'create timesheet', 'type' => 'Attendance - Timesheet']);
        Permission::create(['name' => 'update timesheet', 'type' => 'Attendance - Timesheet']);
        Permission::create(['name' => 'delete timesheet', 'type' => 'Attendance - Timesheet']);
        
        Permission::create(['name' => 'view branch bank details', 'type' => 'Company - Branch Bank Details']);
        Permission::create(['name' => 'create branch bank details', 'type' => 'Company - Branch Bank Details']);
        Permission::create(['name' => 'update branch bank details', 'type' => 'Company - Branch Bank Details']);
        Permission::create(['name' => 'delete branch bank details', 'type' => 'Company - Branch Bank Details']);
        
        Permission::create(['name' => 'view branch', 'type' => 'Company - Branch']);
        Permission::create(['name' => 'create branch', 'type' => 'Company - Branch']);
        Permission::create(['name' => 'update branch', 'type' => 'Company - Branch']);
        Permission::create(['name' => 'delete branch', 'type' => 'Company - Branch']);
        
        Permission::create(['name' => 'view company', 'type' => 'Company - Company']);
        Permission::create(['name' => 'update company', 'type' => 'Company - Company']);
        
        Permission::create(['name' => 'view currency', 'type' => 'Company - Currency']);
        Permission::create(['name' => 'create currency', 'type' => 'Company - Currency']);
        Permission::create(['name' => 'update currency', 'type' => 'Company - Currency']);
        Permission::create(['name' => 'delete currency', 'type' => 'Company - Currency']);
        
        Permission::create(['name' => 'view department', 'type' => 'Company - Department']);
        Permission::create(['name' => 'create department', 'type' => 'Company - Department']);
        Permission::create(['name' => 'update department', 'type' => 'Company - Department']);
        Permission::create(['name' => 'delete department', 'type' => 'Company - Department']);
        
        Permission::create(['name' => 'view employee designation', 'type' => 'Company - Employee Designation']);
        Permission::create(['name' => 'create employee designation', 'type' => 'Company - Employee Designation']);
        Permission::create(['name' => 'update employee designation', 'type' => 'Company - Employee Designation']);
        Permission::create(['name' => 'delete employee designation', 'type' => 'Company - Employee Designation']);
        
        Permission::create(['name' => 'view employee group', 'type' => 'Company - Employee Group']);
        Permission::create(['name' => 'create employee group', 'type' => 'Company - Employee Group']);
        Permission::create(['name' => 'update employee group', 'type' => 'Company - Employee Group']);
        Permission::create(['name' => 'delete employee group', 'type' => 'Company - Employee Group']);
        
        Permission::create(['name' => 'view industry', 'type' => 'Company - Industry']);
        Permission::create(['name' => 'create industry', 'type' => 'Company - Industry']);
        Permission::create(['name' => 'update industry', 'type' => 'Company - Industry']);
        Permission::create(['name' => 'delete industry', 'type' => 'Company - Industry']);
        
        Permission::create(['name' => 'view wage groups', 'type' => 'Company - Wage Groups']);
        Permission::create(['name' => 'create wage groups', 'type' => 'Company - Wage Groups']);
        Permission::create(['name' => 'update wage groups', 'type' => 'Company - Wage Groups']);
        Permission::create(['name' => 'delete wage groups', 'type' => 'Company - Wage Groups']);
        
        Permission::create(['name' => 'view employee bank details', 'type' => 'Employee - Employee Bank Details']);
        Permission::create(['name' => 'create employee bank details', 'type' => 'Employee - Employee Bank Details']);
        Permission::create(['name' => 'update employee bank details', 'type' => 'Employee - Employee Bank Details']);
        Permission::create(['name' => 'delete employee bank details', 'type' => 'Employee - Employee Bank Details']);
        
        Permission::create(['name' => 'view employee profile', 'type' => 'Employee - Employee Profile']);
        Permission::create(['name' => 'create employee profile', 'type' => 'Employee - Employee Profile']);
        Permission::create(['name' => 'update employee profile', 'type' => 'Employee - Employee Profile']);
        Permission::create(['name' => 'delete employee profile', 'type' => 'Employee - Employee Profile']);
        
        Permission::create(['name' => 'view employee family', 'type' => 'Employee - Employee Family']);
        Permission::create(['name' => 'create employee family', 'type' => 'Employee - Employee Family']);
        Permission::create(['name' => 'update employee family', 'type' => 'Employee - Employee Family']);
        Permission::create(['name' => 'delete employee family', 'type' => 'Employee - Employee Family']);
        
        Permission::create(['name' => 'view employee messages', 'type' => 'Employee - Employee Messages']);
        Permission::create(['name' => 'create employee messages', 'type' => 'Employee - Employee Messages']);
        Permission::create(['name' => 'update employee messages', 'type' => 'Employee - Employee Messages']);
        Permission::create(['name' => 'delete employee messages', 'type' => 'Employee - Employee Messages']);
        
        Permission::create(['name' => 'view employee promotion', 'type' => 'Employee - Employee Promotion']);
        Permission::create(['name' => 'create employee promotion', 'type' => 'Employee - Employee Promotion']);
        Permission::create(['name' => 'update employee promotion', 'type' => 'Employee - Employee Promotion']);
        Permission::create(['name' => 'delete employee promotion', 'type' => 'Employee - Employee Promotion']);
        
        Permission::create(['name' => 'view employee qualification', 'type' => 'Employee - Employee Qualification']);
        Permission::create(['name' => 'create employee qualification', 'type' => 'Employee - Employee Qualification']);
        Permission::create(['name' => 'update employee qualification', 'type' => 'Employee - Employee Qualification']);
        Permission::create(['name' => 'delete employee qualification', 'type' => 'Employee - Employee Qualification']);
        
        Permission::create(['name' => 'view employee work experience', 'type' => 'Employee - Employee Work Experience']);
        Permission::create(['name' => 'create employee work experience', 'type' => 'Employee - Employee Work Experience']);
        Permission::create(['name' => 'update employee work experience', 'type' => 'Employee - Employee Work Experience']);
        Permission::create(['name' => 'delete employee work experience', 'type' => 'Employee - Employee Work Experience']);
        
        Permission::create(['name' => 'view employee wage', 'type' => 'Employee - Employee Wage']);
        Permission::create(['name' => 'create employee wage', 'type' => 'Employee - Employee Wage']);
        Permission::create(['name' => 'update employee wage', 'type' => 'Employee - Employee Wage']);
        Permission::create(['name' => 'delete employee wage', 'type' => 'Employee - Employee Wage']);
        
        Permission::create(['name' => 'view employee job history', 'type' => 'Employee - Employee Job History']);
        Permission::create(['name' => 'create employee job history', 'type' => 'Employee - Employee Job History']);
        Permission::create(['name' => 'update employee job history', 'type' => 'Employee - Employee Job History']);
        Permission::create(['name' => 'delete employee job history', 'type' => 'Employee - Employee Job History']);
        
        Permission::create(['name' => 'view company deduction', 'type' => 'Company - Company Deduction']);
        Permission::create(['name' => 'create company deduction', 'type' => 'Company - Company Deduction']);
        Permission::create(['name' => 'update company deduction', 'type' => 'Company - Company Deduction']);
        Permission::create(['name' => 'delete company deduction', 'type' => 'Company - Company Deduction']);
        
        Permission::create(['name' => 'view pay period schedule', 'type' => 'Payroll - Pay Period Schedule']);
        Permission::create(['name' => 'create pay period schedule', 'type' => 'Payroll - Pay Period Schedule']);
        Permission::create(['name' => 'update pay period schedule', 'type' => 'Payroll - Pay Period Schedule']);
        Permission::create(['name' => 'delete pay period schedule', 'type' => 'Payroll - Pay Period Schedule']);
        
        Permission::create(['name' => 'view pay stub account', 'type' => 'Payroll - Pay Stub Account']);
        Permission::create(['name' => 'create pay stub account', 'type' => 'Payroll - Pay Stub Account']);
        Permission::create(['name' => 'update pay stub account', 'type' => 'Payroll - Pay Stub Account']);
        Permission::create(['name' => 'delete pay stub account', 'type' => 'Payroll - Pay Stub Account']);
        
        Permission::create(['name' => 'view pay stub amendment', 'type' => 'Payroll - Pay Stub Amendment']);
        Permission::create(['name' => 'create pay stub amendment', 'type' => 'Payroll - Pay Stub Amendment']);
        Permission::create(['name' => 'update pay stub amendment', 'type' => 'Payroll - Pay Stub Amendment']);
        Permission::create(['name' => 'delete pay stub amendment', 'type' => 'Payroll - Pay Stub Amendment']);
        
        Permission::create(['name' => 'view pay stub entry account link', 'type' => 'Payroll - Pay Stub Entry Account Link']);
        Permission::create(['name' => 'create pay stub entry account link', 'type' => 'Payroll - Pay Stub Entry Account Link']);
        Permission::create(['name' => 'update pay stub entry account link', 'type' => 'Payroll - Pay Stub Entry Account Link']);
        Permission::create(['name' => 'delete pay stub entry account link', 'type' => 'Payroll - Pay Stub Entry Account Link']);
        
        Permission::create(['name' => 'view absence policy', 'type' => 'Policy - Absence Policy']);
        Permission::create(['name' => 'create absence policy', 'type' => 'Policy - Absence Policy']);
        Permission::create(['name' => 'update absence policy', 'type' => 'Policy - Absence Policy']);
        Permission::create(['name' => 'delete absence policy', 'type' => 'Policy - Absence Policy']);
        
        Permission::create(['name' => 'view accrual policy', 'type' => 'Policy - Accrual Policy']);
        Permission::create(['name' => 'create accrual policy', 'type' => 'Policy - Accrual Policy']);
        Permission::create(['name' => 'update accrual policy', 'type' => 'Policy - Accrual Policy']);
        Permission::create(['name' => 'delete accrual policy', 'type' => 'Policy - Accrual Policy']);
        
        Permission::create(['name' => 'view break policy', 'type' => 'Policy - Break Policy']);
        Permission::create(['name' => 'create break policy', 'type' => 'Policy - Break Policy']);
        Permission::create(['name' => 'update break policy', 'type' => 'Policy - Break Policy']);
        Permission::create(['name' => 'delete break policy', 'type' => 'Policy - Break Policy']);
        
        Permission::create(['name' => 'view exception policy', 'type' => 'Policy - Exception Policy']);
        Permission::create(['name' => 'create exception policy', 'type' => 'Policy - Exception Policy']);
        Permission::create(['name' => 'update exception policy', 'type' => 'Policy - Exception Policy']);
        Permission::create(['name' => 'delete exception policy', 'type' => 'Policy - Exception Policy']);
        
        Permission::create(['name' => 'view holiday policy', 'type' => 'Policy - Holiday Policy']);
        Permission::create(['name' => 'create holiday policy', 'type' => 'Policy - Holiday Policy']);
        Permission::create(['name' => 'update holiday policy', 'type' => 'Policy - Holiday Policy']);
        Permission::create(['name' => 'delete holiday policy', 'type' => 'Policy - Holiday Policy']);
        
        Permission::create(['name' => 'view meal policy', 'type' => 'Policy - Meal Policy']);
        Permission::create(['name' => 'create meal policy', 'type' => 'Policy - Meal Policy']);
        Permission::create(['name' => 'update meal policy', 'type' => 'Policy - Meal Policy']);
        Permission::create(['name' => 'delete meal policy', 'type' => 'Policy - Meal Policy']);
        
        Permission::create(['name' => 'view overtime policy', 'type' => 'Policy - Overtime Policy']);
        Permission::create(['name' => 'create overtime policy', 'type' => 'Policy - Overtime Policy']);
        Permission::create(['name' => 'update overtime policy', 'type' => 'Policy - Overtime Policy']);
        Permission::create(['name' => 'delete overtime policy', 'type' => 'Policy - Overtime Policy']);
        
        Permission::create(['name' => 'view policy group', 'type' => 'Policy - Policy Group']);
        Permission::create(['name' => 'create policy group', 'type' => 'Policy - Policy Group']);
        Permission::create(['name' => 'update policy group', 'type' => 'Policy - Policy Group']);
        Permission::create(['name' => 'delete policy group', 'type' => 'Policy - Policy Group']);
        
        Permission::create(['name' => 'view premium policy', 'type' => 'Policy - Premium Policy']);
        Permission::create(['name' => 'create premium policy', 'type' => 'Policy - Premium Policy']);
        Permission::create(['name' => 'update premium policy', 'type' => 'Policy - Premium Policy']);
        Permission::create(['name' => 'delete premium policy', 'type' => 'Policy - Premium Policy']);
        
        Permission::create(['name' => 'view rounding policy', 'type' => 'Policy - Rounding Policy']);
        Permission::create(['name' => 'create rounding policy', 'type' => 'Policy - Rounding Policy']);
        Permission::create(['name' => 'update rounding policy', 'type' => 'Policy - Rounding Policy']);
        Permission::create(['name' => 'delete rounding policy', 'type' => 'Policy - Rounding Policy']);

        Permission::create(['name' => 'view schedule policy', 'type' => 'Policy - Rounding Schedule Policy']);
        Permission::create(['name' => 'create schedule policy', 'type' => 'Policy - Rounding Schedule Policy']);
        Permission::create(['name' => 'update schedule policy', 'type' => 'Policy - Rounding Schedule Policy']);
        Permission::create(['name' => 'delete schedule policy', 'type' => 'Policy - Rounding Schedule Policy']);

        Permission::create(['name' => 'view dashboard', 'type' => 'Dashboard - Admin']);


        // Create Roles
        $superAdminRole = Role::create(['name' => 'super-admin']); //as super-admin
        $adminRole = Role::create(['name' => 'admin']);
        $staffRole = Role::create(['name' => 'staff']);
        $userRole = Role::create(['name' => 'user']);

        // Lets give all permission to super-admin role.
        $allPermissionNames = Permission::pluck('name')->toArray();

        $superAdminRole->givePermissionTo($allPermissionNames);

        // Let's give few permissions to admin role.
        $adminRole->givePermissionTo(['create role', 'view role', 'update role', 'delete role']);
        $adminRole->givePermissionTo(['create permission', 'view permission', 'delete permission']);
        $adminRole->givePermissionTo(['create user', 'view user', 'update user', 'delete user']);
        $adminRole->givePermissionTo(['view dashboard']);
        
        $adminRole->givePermissionTo(['create location', 'view location', 'update location', 'delete location']);
        $adminRole->givePermissionTo(['create station', 'view station', 'update station', 'delete station']);
        $adminRole->givePermissionTo(['create attendance requests', 'view attendance requests', 'update attendance requests', 'delete attendance requests']);
        $adminRole->givePermissionTo(['create mass punch', 'view mass punch', 'update mass punch', 'delete mass punch']);
        $adminRole->givePermissionTo(['create punch', 'view punch', 'update punch', 'delete punch']);
        $adminRole->givePermissionTo(['create timesheet', 'view timesheet', 'update timesheet', 'delete timesheet']);
        $adminRole->givePermissionTo(['create branch bank details', 'view branch bank details', 'update branch bank details', 'delete branch bank details']);
        $adminRole->givePermissionTo(['create branch', 'view branch', 'update branch', 'delete branch']);
        $adminRole->givePermissionTo(['view company', 'update company']);
        $adminRole->givePermissionTo(['create currency', 'view currency', 'update currency', 'delete currency']);
        $adminRole->givePermissionTo(['create department', 'view department', 'update department', 'delete department']);
        $adminRole->givePermissionTo(['create employee designation', 'view employee designation', 'update employee designation', 'delete employee designation']);
        $adminRole->givePermissionTo(['create employee group', 'view employee group', 'update employee group', 'delete employee group']);
        $adminRole->givePermissionTo(['create industry', 'view industry', 'update industry', 'delete industry']);
        $adminRole->givePermissionTo(['create wage groups', 'view wage groups', 'update wage groups', 'delete wage groups']);
        $adminRole->givePermissionTo(['create employee bank details', 'view employee bank details', 'update employee bank details', 'delete employee bank details']);
        $adminRole->givePermissionTo(['create employee profile', 'view employee profile', 'update employee profile', 'delete location']);
        $adminRole->givePermissionTo(['create employee family', 'view employee family', 'update employee family', 'delete employee family']);
        $adminRole->givePermissionTo(['create employee messages', 'view employee messages', 'update employee messages', 'delete employee messages']);
        $adminRole->givePermissionTo(['create employee promotion', 'view employee promotion', 'update employee promotion', 'delete employee promotion']);
        $adminRole->givePermissionTo(['create employee qualification', 'view employee qualification', 'update employee qualification', 'delete employee qualification']);
        $adminRole->givePermissionTo(['create employee work experience', 'view employee work experience', 'update employee work experience', 'delete employee work experience']);
        $adminRole->givePermissionTo(['create employee wage', 'view employee wage', 'update employee wage', 'delete employee wage']);
        $adminRole->givePermissionTo(['create employee job history', 'view employee job history', 'update employee job history', 'delete employee job history']);
        $adminRole->givePermissionTo(['create company deduction', 'view company deduction', 'update company deduction', 'delete company deduction']);
        $adminRole->givePermissionTo(['create pay period schedule', 'view pay period schedule', 'update pay period schedule', 'delete pay period schedule']);
        $adminRole->givePermissionTo(['create pay stub account', 'view pay stub account', 'update pay stub account', 'delete pay stub account']);
        $adminRole->givePermissionTo(['create pay stub amendment', 'view pay stub amendment', 'update pay stub amendment', 'delete pay stub amendment']);
        $adminRole->givePermissionTo(['create pay stub entry account link', 'view pay stub entry account link', 'update pay stub entry account link', 'delete pay stub entry account link']);
        $adminRole->givePermissionTo(['create absence policy', 'view absence policy', 'update absence policy', 'delete absence policy']);
        $adminRole->givePermissionTo(['create accrual policy', 'view accrual policy', 'update accrual policy', 'delete accrual policy']);
        $adminRole->givePermissionTo(['create break policy', 'view break policy', 'update break policy', 'delete break policy']);
        $adminRole->givePermissionTo(['create exception policy', 'view exception policy', 'update exception policy', 'delete exception policy']);
        $adminRole->givePermissionTo(['create holiday policy', 'view holiday policy', 'update holiday policy', 'delete holiday policy']);
        $adminRole->givePermissionTo(['create meal policy', 'view meal policy', 'update meal policy', 'delete meal policy']);
        $adminRole->givePermissionTo(['create overtime policy', 'view overtime policy', 'update overtime policy', 'delete overtime policy']);
        $adminRole->givePermissionTo(['create policy group', 'view policy group', 'update policy group', 'delete policy group']);
        $adminRole->givePermissionTo(['create premium policy', 'view premium policy', 'update premium policy', 'delete premium policy']);
        $adminRole->givePermissionTo(['create rounding policy', 'view rounding policy', 'update rounding policy', 'delete rounding policy']);
        $adminRole->givePermissionTo(['create schedule policy', 'view schedule policy', 'update schedule policy', 'delete schedule policy']);



        // Let's Create User and assign Role to it.
        /*
        $superAdminUser = User::firstOrCreate([
                    'email' => 'superadmin@gmail.com',
                ], [
                    'name' => 'Super Admin',
                    'email' => 'superadmin@gmail.com',
                    'password' => Hash::make ('12345678'),
                ]);

        $superAdminUser->assignRole($superAdminRole);
        */

        $adminUser = User::firstOrCreate([
                            'email' => 'admin@gmail.com'
                        ], [
                            'name' => 'Admin',
                            'email' => 'admin@gmail.com',
                            'password' => Hash::make ('12345678'),
                        ]);

        $adminUser->assignRole($adminRole);


        $staffUser = User::firstOrCreate([
                            'email' => 'staff@gmail.com',
                        ], [
                            'name' => 'Staff',
                            'email' => 'staff@gmail.com',
                            'password' => Hash::make('12345678'),
                        ]);

        $staffUser->assignRole($staffRole);
    }
}