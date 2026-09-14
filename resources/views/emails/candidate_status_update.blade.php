<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <style type="text/css">
        body { margin: 0; background-color: #edf2f7; font-family: Poppins, Arial, sans-serif; color: #27364d; }
        .table-class { width: 100%; max-width: 570px; margin: auto; padding: 30px; background: #fff; box-shadow: 0 2px 0 rgb(0 0 150 / 3%), 2px 4px 0 rgb(0 0 150 / 2%); }
        .status-table { width: 100%; margin: 20px 0; border-collapse: collapse; }
        .status-table td { padding: 8px 0; border-bottom: 1px solid #edf2f7; }
        .action-button { display: table; min-width: 150px; margin: 24px auto; padding: 12px 30px; border-radius: 5px; background: #209776; color: #fff !important; text-align: center; text-decoration: none; }
        .company-name { color: #209776 !important; text-decoration: underline; }
    </style>
</head>
<body>
<div style="border-radius:5px;padding:15px;margin:50px auto;width:100%;box-sizing:border-box;">
    <table class="table-class" role="presentation">
        <tr><td>@include('emails.partials.logo')</td></tr>
        <tr>
            <td>
                <h2 style="margin:0 0 20px;font-size:20px;color:#17243b;">Dear {{ $candidateName }},</h2>
                <p>{{ $messageBody }}</p>
                <table class="status-table" role="presentation">
                    <tr><td><strong>Position:</strong></td><td>{{ $jobTitle }}</td></tr>
                    <tr><td><strong>Company:</strong></td><td>{{ $companyName }}</td></tr>
                    <tr><td><strong>Status:</strong></td><td>{{ $statusText }}</td></tr>
                </table>
                <a href="{{ $actionUrl }}" class="action-button">View Application</a>
                <p>Thanks &amp; Regards,<br>{{ $companyName }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p style="margin-bottom:0;text-align:center;font-size:13px;">
                    <strong>&copy;{{ date('Y') }} <a href="{{ config('app.url') }}" class="company-name">{{ getAppName() }}</a>.</strong>
                    {{ __('messages.all_rights_reserved') }}.
                </p>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
