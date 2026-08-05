<?php

return [
    'title' => 'Support Agents Management',
    'subtitle' => 'List of active support agents with ticket response permissions.',
    'add_new' => '+ New Agent',
    'name' => 'Agent Name',
    'email' => 'Email Address',
    'created_at' => 'Joined Date',
    'actions' => 'Actions',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'delete_confirm' => 'Are you sure you want to delete this agent and revoke their access?',
    'no_supports' => 'No support agents have been defined in the system yet.',
    // موارد جدید create.blade.php
    'create_title' => 'Create Support Agent Account',
    'create_subtitle' => 'Create a new user account with permissions to respond to tickets.',
    'name_label' => 'Agent Full Name',
    'name_placeholder' => 'e.g., John Doe',
    'email_label' => 'Email Address (Login Username)',
    'allowed_departments' => 'Allowed Support Departments:',
    'allowed_departments_help' => 'The agent will only see tickets assigned to the selected departments.',
    'password_label' => 'Password',
    'password_confirmation_label' => 'Confirm Password',
    'submit_create' => 'Create Agent Account',


    // موارد جدید edit.blade.php
    'edit_title' => 'Edit Agent: :name',
    'edit_subtitle' => 'Update user account details and allowed support departments for this agent.',
    'new_password_label' => 'New Password (Optional):',
    'new_password_help' => 'Leave this field blank if you do not wish to change the agent password.',
    'new_password_confirmation_label' => 'Confirm New Password:',
    'cancel' => 'Cancel & Back',
    'submit_update' => 'Save & Update Details',
];