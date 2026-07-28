# Implementation Plan - Bulk User Upload

This plan outlines the implementation of the bulk supporter CSV upload functionality requested.

> [!NOTE]
> The Candidate Dashboard (dedicated read-only dashboard view) is already fully implemented in `app/Livewire/CandidateDashboard.php` and `resources/views/livewire/candidate-dashboard.blade.php`. No further changes are required for it.

## User Review Required

> [!WARNING]
> Bulk uploading users requires careful validation of phone numbers to prevent duplicates. Duplicate phone numbers in the CSV will be skipped. All imported users will be assigned a default password (e.g., `password123`) which they should change upon first login. 

## Proposed Changes

### Excel/CSV Import Class

#### [NEW] [UsersImport.php](file:///c:/Users/User/Herd/onyedozieadmin/app/Imports/UsersImport.php)
Create a new import class to handle parsing the CSV file using `Maatwebsite\Excel`.
- Implement `ToCollection`, `WithHeadingRow`, `WithValidation`.
- Parse columns: `name`, `phone`, `email`, `occupation`, `role`, `lga`, `ward`, `polling_unit`.
- Attempt to map LGA, Ward, and Polling Unit string names to their respective IDs in the database.
- Create users and assign roles based on the CSV data.
- Ignore or log rows with duplicate phone numbers.

### User Management Livewire Component

#### [MODIFY] [UserManagement.php](file:///c:/Users/User/Herd/onyedozieadmin/app/Livewire/UserManagement.php)
- Add a new property `$bulkUploadFile`.
- Add a method `downloadCsvTemplate()` that generates and downloads a template CSV file for admins to fill out.
- Add a method `processBulkUpload()` that accepts the `$bulkUploadFile` and passes it to the `UsersImport` class.
- Handle success/error feedback messages for the admin.

#### [MODIFY] [user-management.blade.php](file:///c:/Users/User/Herd/onyedozieadmin/resources/views/livewire/user-management.blade.php)
- Add a "Bulk Upload CSV" button next to the "New User" button in the header.
- Add a "Download Template" button.
- Add a file input bound to `$bulkUploadFile` and a "Upload" button to trigger `processBulkUpload()`.
- Place these in a small modal or an expandable section below the header.

## Verification Plan

### Automated Tests
- N/A

### Manual Verification
1. Click the "Download Template" button and verify the CSV headers are correct.
2. Fill the CSV template with some test users.
3. Upload the CSV and verify that the users appear in the Campaign Members Directory.
4. Verify that users with invalid LGAs/Wards or duplicate phone numbers are handled gracefully without crashing the app.
