<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable Generator</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --color-primary: #4f46e5;
            --color-primary-dark: #4338ca;
            --color-success: #059669;
            --color-danger: #ef4444;
            --color-warning: #f59e0b;
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --border-hover: #cbd5e1;
            --rounded-sm: 8px;
            --rounded-md: 12px;
            --rounded-lg: 16px;
            --rounded-xl: 20px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter Tight', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #e8f0fe 50%, #f5f3ff 100%);
            min-height: 100vh;
            padding: 40px 30px;
            color: var(--text-primary);
        }

        /* ===== PAGE HEADER ===== */
        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .page-header h1 {
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--color-primary) 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }
        .page-header p {
            font-size: 15px;
            color: var(--text-secondary);
        }

        /* ===== FORM SECTION ===== */
        .form-section {
            background: var(--bg-primary);
            border-radius: var(--rounded-xl);
            padding: 32px;
            box-shadow: var(--shadow-lg);
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }
        .form-section h2 {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-section h2 i {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-radius: var(--rounded-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-primary);
            font-size: 16px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }
        .form-field {
            position: relative;
        }
        .form-field label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form-grid input, .form-grid select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: var(--rounded-md);
            font-size: 14px;
            font-family: inherit;
            background: var(--bg-secondary);
            transition: all 0.2s ease;
            outline: none;
            color: var(--text-primary);
        }
        .form-grid input:focus, .form-grid select:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            background: var(--bg-primary);
        }
        .form-grid input::placeholder {
            color: var(--text-muted);
        }

        /* ===== SUBJECTS ===== */
        .subjects-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 28px 0 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .subjects-title i {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
            border-radius: var(--rounded-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7c3aed;
            font-size: 14px;
        }
        .subject-row {
            display: grid;
            grid-template-columns: 1.5fr 1fr .6fr .8fr auto;
            gap: 12px;
            align-items: center;
            background: var(--bg-tertiary);
            border: 2px solid var(--border-color);
            border-radius: var(--rounded-md);
            padding: 16px 20px;
            margin-bottom: 12px;
            transition: all 0.2s ease;
        }
        .subject-row:hover { 
            border-color: var(--border-hover);
            box-shadow: var(--shadow-sm);
        }
        .subject-row select, .subject-row input {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid var(--border-color);
            border-radius: var(--rounded-sm);
            font-size: 13px;
            font-family: inherit;
            background: var(--bg-primary);
            outline: none;
            transition: all 0.2s ease;
            color: var(--text-primary);
        }
        .subject-row select:focus, .subject-row input:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* ===== BUTTONS ===== */
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: var(--rounded-md);
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn:active { transform: scale(0.98); }
        .btn-primary {
            background: linear-gradient(135deg, var(--color-primary) 0%, #6366f1 100%);
            color: #fff;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
        }
        .btn-primary:hover { 
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.45);
            transform: translateY(-1px);
        }
        .btn-success {
            background: linear-gradient(135deg, var(--color-success) 0%, #10b981 100%);
            color: #fff;
            box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35);
        }
        .btn-success:hover { 
            box-shadow: 0 6px 20px rgba(5, 150, 105, 0.45);
            transform: translateY(-1px);
        }
        .btn-save {
            background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
            color: #fff;
            box-shadow: 0 4px 14px rgba(14, 165, 233, 0.35);
        }
        .btn-save:hover {
            box-shadow: 0 6px 20px rgba(14, 165, 233, 0.45);
            transform: translateY(-1px);
        }
        .btn-danger {
            background: transparent;
            color: var(--color-danger);
            border: 2px solid #fecaca;
            padding: 10px 16px;
            font-size: 13px;
        }
        .btn-danger:hover { 
            background: #fef2f2;
            border-color: #fca5a5;
        }
        .btn-group { 
            display: flex; 
            gap: 12px; 
            margin-top: 24px;
            flex-wrap: wrap;
        }

        /* Save Button - Hidden by default */
        #saveBtn {
            display: none;
        }
        #saveBtn.visible {
            display: inline-flex;
            animation: fadeSlideIn 0.4s ease;
        }
        @keyframes fadeSlideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== TIMETABLE SECTION ===== */
        .schedule-section {
            background: var(--bg-primary);
            border-radius: var(--rounded-xl);
            padding: 32px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            max-width: 1200px;
            margin: 0 auto;
        }
        .schedule-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--bg-tertiary);
        }
        .schedule-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .schedule-header h2 {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .schedule-header-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-radius: var(--rounded-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-success);
            font-size: 18px;
        }
        .schedule-badge {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            color: var(--color-success);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ===== TABLE GRID ===== */
        .tt-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: var(--rounded-lg);
            overflow: hidden;
            border: 2px solid var(--border-color);
        }
        .tt-grid th {
            background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
            padding: 16px 12px;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--border-color);
        }
        .tt-grid td {
            padding: 8px;
            vertical-align: top;
            border-bottom: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
            height: 110px;
            width: 16%;
            background: var(--bg-primary);
        }
        .tt-grid td:last-child {
            border-right: none;
        }
        .tt-grid tr:last-child td {
            border-bottom: none;
        }
        .tt-grid td:first-child {
            width: 120px;
            text-align: center;
            vertical-align: middle;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-secondary);
            background: var(--bg-secondary);
        }

        /* ===== SUBJECT CARDS ===== */
        .tt-card {
            border-radius: var(--rounded-md);
            padding: 12px 14px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 2px solid;
            transition: all 0.2s ease;
            cursor: default;
            position: relative;
            overflow: hidden;
        }
        .tt-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: currentColor;
            opacity: 0.6;
        }
        .tt-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        .tt-card .card-subject {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
            line-height: 1.3;
        }
        .tt-card .card-staff {
            font-size: 11px;
            color: var(--text-secondary);
            line-height: 1.4;
        }
        .tt-card .card-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }
        .tt-card .card-actions a {
            font-size: 13px;
            cursor: pointer;
            transition: all 0.15s ease;
            opacity: 0.7;
        }
        .tt-card .card-actions a:hover { 
            transform: scale(1.2);
            opacity: 1;
        }
        .action-edit { color: var(--text-primary); }
        .action-delete { color: var(--color-danger); }

        /* Color themes */
        .tt-theme-blue   { background: #eff6ff;  border-color: #3b82f6; color: #3b82f6; }
        .tt-theme-orange { background: #fff7ed;  border-color: #f97316; color: #f97316; }
        .tt-theme-green  { background: #f0fdf4;  border-color: #22c55e; color: #22c55e; }
        .tt-theme-red    { background: #fef2f2;  border-color: #ef4444; color: #ef4444; }
        .tt-theme-purple { background: #faf5ff;  border-color: #a855f7; color: #a855f7; }
        .tt-theme-teal   { background: #f0fdfa;  border-color: #14b8a6; color: #14b8a6; }
        .tt-theme-pink   { background: #fdf2f8;  border-color: #ec4899; color: #ec4899; }
        .tt-theme-amber  { background: #fffbeb;  border-color: #f59e0b; color: #f59e0b; }
        .tt-theme-cyan   { background: #ecfeff;  border-color: #06b6d4; color: #06b6d4; }
        .tt-theme-lime   { background: #f7fee7;  border-color: #84cc16; color: #84cc16; }

        /* Empty cell */
        .tt-empty {
            border: 2px dashed var(--border-color);
            border-radius: var(--rounded-md);
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .tt-empty:hover {
            border-color: var(--color-primary);
            background: rgba(79, 70, 229, 0.05);
            color: var(--color-primary);
        }

        /* Break / Lunch row */
        .tt-break td {
            background: linear-gradient(90deg, #fef9c3 0%, #fef08a 100%) !important;
            text-align: center;
            vertical-align: middle;
            height: 50px;
            font-weight: 700;
            font-size: 12px;
            color: #92400e;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #fde68a;
        }
        .tt-lunch td {
            background: linear-gradient(90deg, #fce7f3 0%, #fbcfe8 100%) !important;
            text-align: center;
            vertical-align: middle;
            height: 50px;
            font-weight: 700;
            font-size: 12px;
            color: #9d174d;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #f9a8d4;
        }

        /* Loading spinner */
        .spinner {
            display: inline-block;
            width: 18px; height: 18px;
            border: 3px solid rgba(255,255,255,.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Toast notification */
        .toast {
            position: fixed;
            top: 24px; right: 24px;
            padding: 16px 24px;
            border-radius: var(--rounded-md);
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            z-index: 9999;
            box-shadow: var(--shadow-xl);
            transform: translateX(120%);
            transition: transform .3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .toast.show { transform: translateX(0); }
        .toast-success { background: linear-gradient(135deg, var(--color-success) 0%, #10b981 100%); }
        .toast-error { background: linear-gradient(135deg, var(--color-danger) 0%, #f87171 100%); }

        /* ===== MODAL ===== */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity .25s ease;
        }
        .modal-overlay.show { opacity: 1; }
        .modal-box {
            background: var(--bg-primary);
            border-radius: var(--rounded-xl);
            padding: 32px;
            width: 440px;
            max-width: 95vw;
            box-shadow: var(--shadow-xl);
            transform: translateY(20px) scale(0.95);
            transition: transform .25s ease;
            border: 1px solid var(--border-color);
        }
        .modal-overlay.show .modal-box { 
            transform: translateY(0) scale(1); 
        }
        .modal-box h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .modal-field {
            margin-bottom: 18px;
        }
        .modal-field label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .modal-field input, .modal-field select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: var(--rounded-md);
            font-size: 14px;
            font-family: inherit;
            background: var(--bg-secondary);
            outline: none;
            transition: all 0.2s ease;
            color: var(--text-primary);
        }
        .modal-field input:focus, .modal-field select:focus {
            border-color: var(--color-primary);
            background: var(--bg-primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }
        .btn-secondary {
            padding: 12px 24px;
            border: 2px solid var(--border-color);
            border-radius: var(--rounded-md);
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            background: var(--bg-primary);
            color: var(--text-secondary);
            transition: all 0.2s ease;
        }
        .btn-secondary:hover { 
            background: var(--bg-secondary);
            border-color: var(--border-hover);
        }

        /* Confirm dialog */
        .confirm-box {
            text-align: center;
            padding: 20px;
        }
        .confirm-box .confirm-icon {
            width: 64px; height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .confirm-box .confirm-icon i { 
            font-size: 26px; 
            color: var(--color-danger); 
        }
        .confirm-box p {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }
        .confirm-box .confirm-sub {
            font-size: 13px;
            color: var(--text-secondary);
        }

        /* Conflict list */
        .conflict-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .conflict-list .conflict-item {
            padding: 12px 16px;
            margin-bottom: 8px;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: var(--rounded-md);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .conflict-list .conflict-item strong {
            color: var(--text-primary);
        }
        .conflict-list .conflict-class {
            color: var(--color-danger);
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .subject-row { grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr 1fr; }
            body { padding: 20px 16px; }
            .form-section, .schedule-section { padding: 24px; }
            .page-header h1 { font-size: 26px; }
        }
        @media (max-width: 576px) {
            .form-grid { grid-template-columns: 1fr; }
            .btn-group { flex-direction: column; }
            .btn-group .btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>

<!-- ===== PAGE HEADER ===== -->
<div class="page-header">
    <h1><i class="fa-solid fa-calendar-days"></i> Timetable Scheduler</h1>
    <p>Create and manage your weekly class timetable with ease</p>
</div>

<!-- ===== INPUT FORM ===== -->
<div class="form-section">
    <h2><i class="fa-solid fa-circle-info"></i> Class Information</h2>

    <div class="form-grid">
        <div class="form-field">
            <label>Class Name</label>
            <input type="text" id="class" placeholder="e.g. III IT A">
        </div>
        <div class="form-field">
            <label>Department</label>
            <input type="text" id="dept" placeholder="e.g. Information Technology">
        </div>
        <div class="form-field">
            <label>Year</label>
            <input type="number" id="year" placeholder="e.g. 3" min="1">
        </div>
        <div class="form-field">
            <label>Semester</label>
            <input type="number" id="sem" placeholder="e.g. 5" min="1">
        </div>
        <div class="form-field">
            <label>Academic Year</label>
            <input type="text" id="academic_year" placeholder="e.g. 2025-2026">
        </div>
    </div>

    <div class="subjects-title"><i class="fa-solid fa-book-open"></i> Subject Allocation</div>
    <div id="subjects-container"></div>

    <div class="btn-group">
        <button class="btn btn-primary" onclick="addSubject()"><i class="fa-solid fa-plus"></i> Add Subject</button>
        <button class="btn btn-success" id="generateBtn" onclick="submitForm()"><i class="fa-solid fa-wand-magic-sparkles"></i> Generate Timetable</button>
        <button class="btn btn-save" id="saveBtn" onclick="fixTimetable()"><i class="fa-solid fa-database"></i> Save to Database</button>
    </div>
</div>

<!-- ===== TIMETABLE DISPLAY ===== -->
<div id="timetable-display"></div>

<div id="toast" class="toast"></div>

<!-- ===== EDIT MODAL ===== -->
<div id="editModal" class="modal-overlay" style="display:none" onclick="if(event.target===this)closeEditModal()">
    <div class="modal-box">
        <h3><i class="fa-solid fa-pen-to-square" style="color:#4f46e5"></i> Edit Period</h3>
        <div class="modal-field">
            <label>Subject</label>
            <input type="text" id="editSubject" placeholder="Subject Name">
        </div>
        <div class="modal-field">
            <label>Staff</label>
            <select id="editStaff"></select>
        </div>
        <input type="hidden" id="editDay">
        <input type="hidden" id="editHour">
        <div class="modal-actions">
            <button class="btn-secondary" onclick="closeEditModal()">Cancel</button>
            <button class="btn btn-primary" onclick="saveEdit()"><i class="fa-solid fa-check"></i> Save</button>
        </div>
    </div>
</div>

<!-- ===== DELETE CONFIRM MODAL ===== -->
<div id="deleteModal" class="modal-overlay" style="display:none" onclick="if(event.target===this)closeDeleteModal()">
    <div class="modal-box">
        <div class="confirm-box">
            <div class="confirm-icon"><i class="fa-solid fa-trash-can"></i></div>
            <p>Delete this period?</p>
            <p class="confirm-sub" id="deleteInfo"></p>
            <input type="hidden" id="deleteDay">
            <input type="hidden" id="deleteHour">
            <div class="modal-actions" style="justify-content:center;margin-top:22px;">
                <button class="btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                <button class="btn btn-danger" style="background:#ef4444;color:#fff;border:none;padding:10px 22px;font-size:14px;" onclick="confirmDelete()"><i class="fa-solid fa-trash-can"></i> Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- ===== VIEW TIMETABLE MODAL ===== -->
<div id="viewModal" class="modal-overlay" style="display:none" onclick="if(event.target===this)closeViewModal()">
    <div class="modal-box" style="width: 90vw; max-width: 1200px;">
        <h3><i class="fa-solid fa-eye" style="color:#4f46e5"></i> View Timetable</h3>
        <div id="viewTimetableContent"></div>
        <div class="modal-actions">
            <button class="btn-secondary" onclick="closeViewModal()">Close</button>
        </div>
    </div>
</div>

<script>
let staffList = [];
let timetabledata = {};
let currentTimetable = null; // stores the live timetable data
let currentConflicts = null; // stores current conflicts for viewing

const COLORS = ['blue','orange','green','red','purple','teal','pink','amber','cyan','lime'];

const TIME_SLOTS = [
    { hour: 1, label: '8:45 - 9:45' },
    { hour: 2, label: '9:45 - 10:45' },
    { type: 'break', label: '10:45 - 11:05', text: 'BREAK' },
    { hour: 3, label: '11:05 - 12:05' },
    { hour: 4, label: '12:05 - 12:55' },
    { type: 'lunch', label: '12:55 - 1:45', text: 'LUNCH BREAK' },
    { hour: 5, label: '1:45 - 2:45' },
    { type: 'break', label: '2:45 - 3:00', text: 'BREAK' },
    { hour: 6, label: '3:00 - 3:50' },
    { hour: 7, label: '3:50 - 4:40' }
];

// Fetch staff from DB
$(document).ready(function(){
    $.get("get_staff.php", function(data){
        staffList = JSON.parse(data);
        addSubject();
    });
});
function fixTimetable(){
    if(!currentTimetable){
        showToast('No timetable to send', 'error');
        return;
    }
    // Count filled periods across Mon-Fri, periods 1..7
    const days = ['Mon','Tue','Wed','Thu','Fri'];
    let filled = 0;
    days.forEach(d => {
        for(let h=1; h<=7; h++){
            if(currentTimetable[d] && currentTimetable[d][h] && currentTimetable[d][h].subject){
                filled++;
            }
        }
    });

    if(filled < 35){
        showToast(`Incomplete timetable: ${filled}/35 periods filled`, 'error');
        return;
    }

    // Find the Save button to show spinner/disable
    const btn = document.getElementById('saveBtn');
    let orig = null;
    if(btn){
        orig = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span> Saving...';
    }

    $.ajax({
        url: 'fixtt.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            class: $("#class").val(),
            dept: $("#dept").val(),
            year: parseInt($("#year").val()),
            sem: parseInt($("#sem").val()),
            academic_year: $("#academic_year").val(),
            timetable: currentTimetable,
            override: false
        }),
        success: function(response){
            if(btn){ btn.disabled = false; btn.innerHTML = orig; }

            // Handle fixtt.php JSON response and show SweetAlert2 modals
            try{
                // response is expected as an object (jQuery parses JSON)
                if(response && response.status === 'conflicts'){
                    currentConflicts = response.conflicts;

                    // build a readable list of conflicts
                    let smlist = '';
                    let cmlist = '';
                    let list = '<ul style="text-align:left;margin:0;padding-left:18px;">';
                    if(response.conflicts.staffconflicts && response.conflicts.staffconflicts.length > 0){
                        response.conflicts.staffconflicts.forEach(c => {
                            smlist += `<li>Staff conflict: ${c.day}, Period ${c.hour_no} with class ${c.conflict_with_class}</li>`;
                        });
                        list += '<li><button class="btn btn-primary" onclick="showStaffConflicts()">View Staff Conflicts</button></li>';
                    }
                    if(response.conflicts.classconflicts && response.conflicts.classconflicts.length > 0){
                        response.conflicts.classconflicts.forEach(c => {
                            cmlist += `<li>Class conflict: ${c.day}, Period ${c.hour_no} with class ${c.conflict_with_class}</li>`;
                        });
                        list += '<li><button class="btn btn-success" onclick="showClassConflicts()">View Class Conflicts</button></li>';
                    }
                    list += '<li>Do you want to proceed anyway? Then press OK. This will override the old data.</li>';
                    list += '</ul>';

                    Swal.fire({
                        title: 'Conflicting Periods',
                        html: list,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                        cancelButtonText: 'No',
                        allowOutsideClick: false
                    }).then(result => {
                        if(result.isConfirmed){
                            // user chose to proceed; resend same payload with override=true
                            if(btn){ btn.disabled = true; btn.innerHTML = '<span class="spinner"></span> Overriding...'; }

                            $.ajax({
                                url: 'fixtt.php',
                                type: 'POST',
                                contentType: 'application/json',
                                data: JSON.stringify({
                                    class: $("#class").val(),
                                    dept: $("#dept").val(),
                                    year: parseInt($("#year").val()),
                                    sem: parseInt($("#sem").val()),
                                    academic_year: $("#academic_year").val(),
                                    timetable: currentTimetable,
                                    override: true
                                }),
                                success: function(resp){
                                    if(btn){ btn.disabled = false; btn.innerHTML = orig; }
                                    try{
                                        if(resp && resp.status === 'conflicts'){
                                            Swal.fire({
                                                title: 'Still Conflicts',
                                                html: '<pre style="text-align:left">'+JSON.stringify(resp.conflicts, null, 2)+'</pre>',
                                                icon: 'warning'
                                            });
                                        } else if(resp && resp.status === 'ok'){
                                            Swal.fire({
                                                title: 'Saved',
                                                text: resp.message || 'Override applied successfully',
                                                icon: 'success'
                                            });
                                        } else {
                                            Swal.fire({ title: 'Response', text: JSON.stringify(resp), icon: 'info' });
                                        }
                                    }catch(e){
                                        console.error('Error handling override response', e);
                                    }
                                    console.log('fixtt.php override response:', resp);
                                },
                                error: function(err){
                                    if(btn){ btn.disabled = false; btn.innerHTML = orig; }
                                    Swal.fire({ title: 'Error', text: 'Failed to send override', icon: 'error' });
                                    console.error('fixtt.php override error:', err);
                                }
                            });
                        }
                    });

                } else if(response && response.status === 'ok'){
                    Swal.fire({
                        title: 'No Conflicts',
                        text: response.message || 'No periods conflict',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });

                } else {
                    Swal.fire({
                        title: 'Response',
                        text: JSON.stringify(response),
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                }
            }catch(e){
                console.error('Error handling fixtt response', e);
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to parse response from server',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }

            showToast('Timetable sent to fixtt.php', 'success');
            console.log('fixtt.php response:', response);
        },
        error: function(err){
            if(btn){ btn.disabled = false; btn.innerHTML = orig; }
            showToast('Failed to send timetable', 'error');
            console.error('fixtt.php error:', err);
        }
    });
}
function addSubject(){
    let options = '';
    staffList.forEach(staff => {
        options += `<option value="${staff.id}">
            ${staff.name} (${staff.dept})
        </option>`;
    });

    let html = `
        <div class="subject-row">
            <select class="staff_id">
                ${options}
            </select>
            <input type="text" class="subject" placeholder="Subject Name">
            <input type="number" class="periods" placeholder="Periods" min="1">
            <select class="type">
                <option value="theory">Theory</option>
                <option value="lab">Lab</option>
            </select>
            <button class="btn btn-danger" onclick="removeSubject(this)"><i class="fa-solid fa-trash-can"></i></button>
        </div>
    `;
    $("#subjects-container").append(html);
}

function removeSubject(btn){
    $(btn).closest(".subject-row").remove();
}

function showToast(msg, type){
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className = 'toast toast-' + type + ' show';
    setTimeout(()=>{ t.classList.remove('show'); }, 3000);
}

function submitForm(){
    let subjects = [];
    timetableData = {}; // reset timetable data on new submission

    $(".subject-row").each(function(){
        subjects.push({
            staff_id: parseInt($(this).find(".staff_id").val()),
            subject: $(this).find(".subject").val(),
            periods: parseInt($(this).find(".periods").val()),
            type: $(this).find(".type").val()
        });
    });

    let payload = {
        class: $("#class").val(),
        dept: $("#dept").val(),
        year: parseInt($("#year").val()),
        sem: parseInt($("#sem").val()),
        academic_year: $("#academic_year").val(),
        subjects: subjects
    };

    const btn = document.getElementById('generateBtn');
    btn.innerHTML = '<span class="spinner"></span> Generating...';
    btn.disabled = true;

    $.ajax({
        url: "api.php",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify(payload),
        success: function(response){
            btn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles"></i> Generate Timetable';
            btn.disabled = false;
            showToast('Timetable generated successfully!', 'success');
            console.log(response);
            timetableData = response.timetable;
            currentTimetable = response.timetable;
            renderTimetable(currentTimetable);
            // Show the save button after successful generation
            document.getElementById('saveBtn').classList.add('visible');
        },
        error: function(err){
            btn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles"></i> Generate Timetable';
            btn.disabled = false;
            showToast('Error generating timetable', 'error');
            console.log(err);
        }
    });
}

// Build a color map: each unique subject gets a consistent color
function buildColorMap(data){
    const map = {};
    let idx = 0;
    const days = ["Mon","Tue","Wed","Thu","Fri"];
    days.forEach(d=>{
        for(let h=1;h<=7;h++){
            if(data[d] && data[d][h] && data[d][h].subject){
                const subj = data[d][h].subject;
                if(!(subj in map)){
                    map[subj] = COLORS[idx % COLORS.length];
                    idx++;
                }
            }
        }
    });
    return map;
}

function renderTimetable(data, target = '#timetable-display'){
    const days = ["Mon","Tue","Wed","Thu","Fri"];
    const daysFull = ["Monday","Tuesday","Wednesday","Thursday","Friday"];
    const colorMap = buildColorMap(data);

    // Count filled slots
    let filledCount = 0;
    days.forEach(d => {
        for(let h=1; h<=7; h++){
            if(data[d] && data[d][h] && data[d][h].subject) filledCount++;
        }
    });

    let html = `
    <div class="schedule-section">
        <div class="schedule-header">
            <div class="schedule-header-left">
                <div class="schedule-header-icon"><i class="fa-solid fa-table-cells-large"></i></div>
                <h2>Weekly Schedule</h2>
            </div>
            <div class="schedule-badge">
                <i class="fa-solid fa-check-circle"></i> ${filledCount}/35 Periods
            </div>
        </div>
        <table class="tt-grid">
            <thead>
                <tr>
                    <th>Time</th>`;
    daysFull.forEach(d => html += `<th>${d}</th>`);
    html += `</tr></thead><tbody>`;

    TIME_SLOTS.forEach(slot => {
        // Break row
        if(slot.type === 'break'){
            html += `<tr class="tt-break">
                <td>${slot.label}</td>
                <td colspan="5"><i class="fa-solid fa-mug-hot" style="margin-right:6px;"></i>${slot.text}</td>
            </tr>`;
            return;
        }
        // Lunch row
        if(slot.type === 'lunch'){
            html += `<tr class="tt-lunch">
                <td>${slot.label}</td>
                <td colspan="5"><i class="fa-solid fa-utensils" style="margin-right:6px;"></i>${slot.text}</td>
            </tr>`;
            return;
        }

        // Normal period row
        html += `<tr><td>${slot.label}</td>`;
        days.forEach(d => {
            let cell = '';
            if(data[d] && data[d][slot.hour]){
                const entry = data[d][slot.hour];
                if(entry.subject){
                    const staff = staffList.find(s => s.id == entry.staff_id);
                    const name = staff ? staff.name : 'Unknown';
                    const theme = colorMap[entry.subject] || 'blue';
                    cell = `
                        <div class="tt-card tt-theme-${theme}">
                            <div>
                                <div class="card-subject">${entry.subject}</div>
                                <div class="card-staff">${name}</div>
                            </div>
                            <div class="card-actions">
                                <a class="action-edit" title="Edit" onclick="openEditModal('${d}',${slot.hour})"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a class="action-delete" title="Delete" onclick="openDeleteModal('${d}',${slot.hour})"><i class="fa-solid fa-trash-can"></i></a>
                            </div>
                        </div>`;
                } else {
                    cell = `<div class="tt-empty" onclick="openEditModal('${d}',${slot.hour})">Click to add</div>`;
                }
            } else {
                cell = `<div class="tt-empty" onclick="openEditModal('${d}',${slot.hour})">Click to add</div>`;
            }
            html += `<td>${cell}</td>`;
        });
        html += `</tr>`;
    });

    html += `</tbody></table></div>`;
    $(target).html(html);
}

// ===== EDIT MODAL =====
function openEditModal(day, hour){
    if(!currentTimetable) return;
    // Ensure day/hour slot exists (may be empty)
    if(!currentTimetable[day]) currentTimetable[day] = {};
    const entry = currentTimetable[day][hour] || { staff_id: null, subject: null };
    const isAdd = !entry.subject;

    // Update modal title
    $('#editModal .modal-box h3').html(
        isAdd
        ? '<i class="fa-solid fa-plus-circle" style="color:#059669"></i> Add Period'
        : '<i class="fa-solid fa-pen-to-square" style="color:#4f46e5"></i> Edit Period'
    );

    // populate staff dropdown
    let opts = '';
    staffList.forEach(s => {
        const sel = (s.id == entry.staff_id) ? 'selected' : '';
        opts += `<option value="${s.id}" ${sel}>${s.name} (${s.dept})</option>`;
    });
    $('#editStaff').html(opts);
    $('#editSubject').val(entry.subject || '');
    $('#editDay').val(day);
    $('#editHour').val(hour);

    const modal = document.getElementById('editModal');
    modal.style.display = 'flex';
    setTimeout(()=> modal.classList.add('show'), 10);
}

function closeEditModal(){
    const modal = document.getElementById('editModal');
    modal.classList.remove('show');
    setTimeout(()=> modal.style.display = 'none', 250);
}

function saveEdit(){
    const day = $('#editDay').val();
    const hour = parseInt($('#editHour').val());
    const newSubject = $('#editSubject').val().trim();
    const newStaffId = parseInt($('#editStaff').val());

    if(!newSubject){
        showToast('Subject name cannot be empty', 'error');
        return;
    }

    // Update live timetable data
    currentTimetable[day][hour] = {
        staff_id: newStaffId,
        subject: newSubject,
        type: currentTimetable[day][hour].type || 'theory'
    };

    closeEditModal();
    renderTimetable(currentTimetable);
    showToast('Period updated successfully!', 'success');
}

// ===== DELETE MODAL =====
function openDeleteModal(day, hour){
    if(!currentTimetable || !currentTimetable[day] || !currentTimetable[day][hour]) return;
    const entry = currentTimetable[day][hour];
    const staff = staffList.find(s => s.id == entry.staff_id);
    const staffName = staff ? staff.name : 'Unknown';

    $('#deleteDay').val(day);
    $('#deleteHour').val(hour);
    $('#deleteInfo').text(`${entry.subject} — ${staffName} (${day}, Period ${hour})`);

    const modal = document.getElementById('deleteModal');
    modal.style.display = 'flex';
    setTimeout(()=> modal.classList.add('show'), 10);
}

function closeDeleteModal(){
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('show');
    setTimeout(()=> modal.style.display = 'none', 250);
}

function confirmDelete(){
    const day = $('#deleteDay').val();
    const hour = parseInt($('#deleteHour').val());

    // Clear the slot
    currentTimetable[day][hour] = {
        staff_id: null,
        subject: null
    };

    closeDeleteModal();
    renderTimetable(currentTimetable);
    showToast('Period deleted', 'success');
}

function closeViewModal(){
    const modal = document.getElementById('viewModal');
    modal.classList.remove('show');
    setTimeout(()=> modal.style.display = 'none', 250);
}

function viewClassTimetable(){
    $('#viewModal .modal-box h3').html('<i class="fa-solid fa-eye" style="color:#4f46e5"></i> View Existing Timetable');
    const payload = {
        class: $("#class").val(),
        dept: $("#dept").val(),
        year: parseInt($("#year").val()),
        sem: parseInt($("#sem").val()),
        academic_year: $("#academic_year").val()
        // Assuming api.php returns existing timetable if no subjects
    };
    $('#viewTimetableContent').html('<div id="loading">Loading timetable...</div>');
    const modal = document.getElementById('viewModal');
    modal.style.display = 'flex';
    setTimeout(()=> modal.classList.add('show'), 10);
    $.ajax({
        url: "api.php",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify(payload),
        success: function(response){
            if(response.timetable){
                renderTimetable(response.timetable, '#viewTimetableContent');
            } else {
                $('#viewTimetableContent').html('<p>No existing timetable found</p>');
            }
        },
        error: function(){
            $('#viewTimetableContent').html('<p>Failed to fetch timetable</p>');
        }
    });
}

function viewStaffConflicts(){
    if(!currentConflicts || !currentConflicts.staffconflicts || currentConflicts.staffconflicts.length === 0) return;
    $('#viewModal .modal-box h3').html('<i class="fa-solid fa-eye" style="color:#4f46e5"></i> View Conflicting Timetables');
    const uniqueClasses = [...new Set(currentConflicts.staffconflicts.map(c => c.conflict_with_class))];
    $('#viewTimetableContent').html('<div id="loading">Loading timetables...</div>');
    const modal = document.getElementById('viewModal');
    modal.style.display = 'flex';
    setTimeout(()=> modal.classList.add('show'), 10);

    let promises = uniqueClasses.map(cls => {
        return $.ajax({
            url: "api.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                class: cls
                // Assuming other params are optional or default
            })
        });
    });

    Promise.all(promises).then(responses => {
        let html = '<h4>Conflicting Class Timetables</h4>';
        responses.forEach((response, index) => {
            if(response.timetable){
                html += `<h5>Class: ${uniqueClasses[index]}</h5>`;
                const tempDiv = document.createElement('div');
                renderTimetable(response.timetable, tempDiv);
                html += tempDiv.innerHTML;
            }
        });
        $('#viewTimetableContent').html(html);
    }).catch(() => {
        $('#viewTimetableContent').html('<p>Failed to load timetables</p>');
    });
}
function showStaffConflicts(){
    if(!currentConflicts || !currentConflicts.staffconflicts || currentConflicts.staffconflicts.length === 0) return;
    $('#viewModal .modal-box h3').html('<i class="fa-solid fa-exclamation-triangle" style="color:#f59e0b"></i> Staff Conflicts');
    let html = '<div style="max-height: 400px; overflow-y: auto;"><ul class="conflict-list">';
    currentConflicts.staffconflicts.forEach(c => {
        html += `<li class="conflict-item"><strong>${c.day}, Period ${c.hour_no}</strong> - Conflict with class: <span class="conflict-class">${c.conflict_with_class}</span></li>`;
    });
    html += '</ul></div>';
    $('#viewTimetableContent').html(html);
    const modal = document.getElementById('viewModal');
    modal.style.display = 'flex';
    setTimeout(()=> modal.classList.add('show'), 10);
}

function showClassConflicts(){
    if(!currentConflicts || !currentConflicts.classconflicts || currentConflicts.classconflicts.length === 0) return;
    $('#viewModal .modal-box h3').html('<i class="fa-solid fa-calendar-times" style="color:#ef4444"></i> Class Conflicts');
    let html = '<div style="max-height: 400px; overflow-y: auto;"><ul class="conflict-list">';
    currentConflicts.classconflicts.forEach(c => {
        html += `<li class="conflict-item"><strong>${c.day}, Period ${c.hour_no}</strong> - Conflict with class: <span class="conflict-class">${c.conflict_with_class}</span></li>`;
    });
    html += '</ul></div>';
    $('#viewTimetableContent').html(html);
    const modal = document.getElementById('viewModal');
    modal.style.display = 'flex';
    setTimeout(()=> modal.classList.add('show'), 10);
}
</script>
</body>
</html>