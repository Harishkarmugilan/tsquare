<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable Generator</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            padding: 30px;
            color: #333;
        }

        /* ===== FORM SECTION ===== */
        .form-section {
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
            margin-bottom: 30px;
        }
        .form-section h2 {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 20px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 14px;
            margin-bottom: 10px;
        }
        .form-grid input, .form-grid select {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            background: #fafafa;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }
        .form-grid input:focus, .form-grid select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
            background: #fff;
        }

        /* ===== SUBJECTS ===== */
        .subjects-title {
            font-size: 17px;
            font-weight: 600;
            color: #1a1a2e;
            margin: 24px 0 14px;
        }
        .subject-row {
            display: grid;
            grid-template-columns: 1.5fr 1fr .6fr .8fr auto;
            gap: 10px;
            align-items: center;
            background: #f8f9fb;
            border: 1.5px solid #eee;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 10px;
            transition: border-color .2s;
        }
        .subject-row:hover { border-color: #c7d2fe; }
        .subject-row select, .subject-row input {
            width: 100%;
            padding: 9px 12px;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            background: #fff;
            outline: none;
            transition: border-color .2s;
        }
        .subject-row select:focus, .subject-row input:focus {
            border-color: #4f46e5;
        }

        /* ===== BUTTONS ===== */
        .btn {
            padding: 10px 22px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: transform .15s, box-shadow .2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn:active { transform: scale(.97); }
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff;
            box-shadow: 0 4px 14px rgba(79,70,229,.3);
        }
        .btn-primary:hover { box-shadow: 0 6px 20px rgba(79,70,229,.4); }
        .btn-success {
            background: linear-gradient(135deg, #059669, #10b981);
            color: #fff;
            box-shadow: 0 4px 14px rgba(5,150,105,.3);
        }
        .btn-success:hover { box-shadow: 0 6px 20px rgba(5,150,105,.4); }
        .btn-danger {
            background: transparent;
            color: #ef4444;
            border: 1.5px solid #fca5a5;
            padding: 8px 14px;
            font-size: 13px;
        }
        .btn-danger:hover { background: #fef2f2; }
        .btn-group { display: flex; gap: 10px; margin-top: 18px; }

        /* ===== TIMETABLE SECTION ===== */
        .schedule-section {
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
        }
        .schedule-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
        }
        .schedule-header h2 {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a2e;
        }
        .schedule-header .edit-icon {
            color: #9ca3af;
            cursor: pointer;
            font-size: 16px;
        }

        /* ===== TABLE GRID ===== */
        .tt-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .tt-grid th {
            background: #f8f9fb;
            padding: 14px 10px;
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .5px;
            border-bottom: 2px solid #e5e7eb;
        }
        .tt-grid th:first-child { border-radius: 12px 0 0 0; }
        .tt-grid th:last-child  { border-radius: 0 12px 0 0; }

        .tt-grid td {
            padding: 6px;
            vertical-align: top;
            border-bottom: 1px solid #f0f0f0;
            height: 110px;
            width: 16%;
        }
        .tt-grid td:first-child {
            width: 120px;
            text-align: center;
            vertical-align: middle;
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
            background: #fafbfc;
        }

        /* ===== SUBJECT CARDS ===== */
        .tt-card {
            border-radius: 12px;
            padding: 12px 14px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 2px solid;
            transition: transform .15s, box-shadow .2s;
            cursor: default;
        }
        .tt-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,.08);
        }
        .tt-card .card-subject {
            font-size: 14px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 4px;
        }
        .tt-card .card-staff {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.4;
        }
        .tt-card .card-actions {
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }
        .tt-card .card-actions a {
            font-size: 14px;
            cursor: pointer;
            transition: transform .15s;
        }
        .tt-card .card-actions a:hover { transform: scale(1.2); }
        .action-edit { color: #374151; }
        .action-delete { color: #ef4444; }

        /* Color themes */
        .tt-theme-blue   { background: #eff6ff;  border-color: #60a5fa; }
        .tt-theme-orange { background: #fff7ed;  border-color: #fb923c; }
        .tt-theme-green  { background: #f0fdf4;  border-color: #4ade80; }
        .tt-theme-red    { background: #fef2f2;  border-color: #f87171; }
        .tt-theme-purple { background: #f5f3ff;  border-color: #a78bfa; }
        .tt-theme-teal   { background: #f0fdfa;  border-color: #2dd4bf; }
        .tt-theme-pink   { background: #fdf2f8;  border-color: #f472b6; }
        .tt-theme-amber  { background: #fffbeb;  border-color: #fbbf24; }
        .tt-theme-cyan   { background: #ecfeff;  border-color: #22d3ee; }
        .tt-theme-lime   { background: #f7fee7;  border-color: #a3e635; }

        /* Empty cell */
        .tt-empty {
            border: 2px dashed #e5e7eb;
            border-radius: 12px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c4c4c4;
            font-size: 13px;
            cursor: pointer;
            transition: border-color .2s, background .2s;
        }
        .tt-empty:hover {
            border-color: #a5b4fc;
            background: #f5f3ff;
            color: #7c3aed;
        }

        /* Break / Lunch row */
        .tt-break td {
            background: linear-gradient(90deg, #fef9c3 0%, #fef08a 100%);
            text-align: center;
            vertical-align: middle;
            height: 48px;
            font-weight: 600;
            font-size: 13px;
            color: #92400e;
            letter-spacing: .5px;
            border-bottom: 1px solid #fde68a;
        }
        .tt-break td:first-child {
            background: linear-gradient(90deg, #fef9c3 0%, #fef08a 100%);
            color: #92400e;
        }
        .tt-lunch td {
            background: linear-gradient(90deg, #fce7f3 0%, #fbcfe8 100%);
            text-align: center;
            vertical-align: middle;
            height: 48px;
            font-weight: 600;
            font-size: 13px;
            color: #9d174d;
            letter-spacing: .5px;
            border-bottom: 1px solid #f9a8d4;
        }
        .tt-lunch td:first-child {
            background: linear-gradient(90deg, #fce7f3 0%, #fbcfe8 100%);
            color: #9d174d;
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
            padding: 14px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            z-index: 9999;
            box-shadow: 0 8px 24px rgba(0,0,0,.15);
            transform: translateX(120%);
            transition: transform .3s ease;
        }
        .toast.show { transform: translateX(0); }
        .toast-success { background: #059669; }
        .toast-error { background: #dc2626; }

        /* ===== MODAL ===== */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity .25s;
        }
        .modal-overlay.show { opacity: 1; }
        .modal-box {
            background: #fff;
            border-radius: 16px;
            padding: 28px 32px;
            width: 420px;
            max-width: 95vw;
            box-shadow: 0 20px 60px rgba(0,0,0,.2);
            transform: translateY(20px);
            transition: transform .25s;
        }
        .modal-overlay.show .modal-box { transform: translateY(0); }
        .modal-box h3 {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .modal-field {
            margin-bottom: 14px;
        }
        .modal-field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 5px;
        }
        .modal-field input, .modal-field select {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            background: #fafafa;
            outline: none;
            transition: border-color .2s;
        }
        .modal-field input:focus, .modal-field select:focus {
            border-color: #4f46e5;
            background: #fff;
        }
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        .btn-secondary {
            padding: 10px 22px;
            border: 1.5px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            background: #fff;
            color: #374151;
            transition: background .15s;
        }
        .btn-secondary:hover { background: #f3f4f6; }

        /* Confirm dialog */
        .confirm-box {
            text-align: center;
            padding: 30px;
        }
        .confirm-box .confirm-icon {
            width: 56px; height: 56px;
            border-radius: 50%;
            background: #fef2f2;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }
        .confirm-box .confirm-icon i { font-size: 24px; color: #ef4444; }
        .confirm-box p {
            font-size: 15px;
            color: #374151;
            margin-bottom: 8px;
        }
        .confirm-box .confirm-sub {
            font-size: 13px;
            color: #9ca3af;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .subject-row { grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr 1fr; }
            body { padding: 14px; }
        }
    </style>
</head>
<body>

<!-- ===== INPUT FORM ===== -->
<div class="form-section">
    <h2><i class="fa-solid fa-calendar-plus" style="color:#4f46e5;margin-right:8px;"></i> Timetable Input Form</h2>

    <div class="form-grid">
        <input type="text" id="class" placeholder="Class (e.g. III IT A)">
        <input type="text" id="dept" placeholder="Department">
        <input type="number" id="year" placeholder="Year" min="1">
        <input type="number" id="sem" placeholder="Semester" min="1">
        <input type="text" id="academic_year" placeholder="Academic Year (2025-2026)">
    </div>

    <div class="subjects-title"><i class="fa-solid fa-book" style="color:#6366f1;margin-right:6px;"></i> Subjects</div>
    <div id="subjects-container"></div>

    <div class="btn-group">
        <button class="btn btn-primary" onclick="addSubject()"><i class="fa-solid fa-plus"></i> Add Subject</button>
        <button class="btn btn-success" id="generateBtn" onclick="submitForm()"><i class="fa-solid fa-wand-magic-sparkles"></i> Generate Timetable</button>
    </div>
</div>

<!-- ===== TIMETABLE DISPLAY ===== -->
<div id="timetable-display"></div>
<button onclick="fixTimetable()">Fix timetable</button>

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

<script>
let staffList = [];
let timetabledata = {};
let currentTimetable = null; // stores the live timetable data

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

    // Find the Fix button (the simple one on the page) to show spinner/disable
    const btn = document.querySelector('button[onclick="fixTimetable()"]');
    let orig = null;
    if(btn){
        orig = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span> Sending...';
    }

    const payload = {
    class: $("#class").val(),
    dept: $("#dept").val(),
    year: parseInt($("#year").val()),
    sem: parseInt($("#sem").val()),
    academic_year: $("#academic_year").val(),
    timetable: currentTimetable
};

$.ajax({
    url: 'fixtt.php',
    type: 'POST',
    contentType: 'application/json',
    dataType: 'json',
    data: JSON.stringify(payload),

    success: function(response){

        if(btn){
            btn.disabled = false;
            btn.innerHTML = orig;
        }

        try{

            if(response && response.status === 'conflicts'){

                let list = '<ul style="text-align:left;margin:0;padding-left:18px;">';

                if(response.conflicts && response.conflicts.staffconflicts){
                    response.conflicts.staffconflicts.forEach(c=>{
                        list += `<li>Staff ${c.staff_id} conflict with ${c.conflict_with_class} — ${c.day}, Period ${c.hour_no}</li>`;
                    });
                }

                if(response.conflicts && response.conflicts.classconflicts){
                    response.conflicts.classconflicts.forEach(c=>{
                        list += `<li>Class conflict — ${c.day}, Period ${c.hour_no}</li>`;
                    });
                }

                list += '<li><b>Do you want to proceed anyway? This will override the old data.</b></li>';
                list += '</ul>';

                Swal.fire({
                    title: 'Conflicting Periods',
                    html: list,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Override',
                    cancelButtonText: 'Cancel',
                    allowOutsideClick: false
                }).then(result => {

                    if(result.isConfirmed){

                        if(btn){
                            btn.disabled = true;
                            btn.innerHTML = '<span class="spinner"></span> Overriding...';
                        }

                        payload.override = true;

                        $.ajax({
                            url: 'fixtt.php',
                            type: 'POST',
                            contentType: 'application/json',
                            dataType: 'json',
                            data: JSON.stringify(payload),

                            success: function(resp){

                                if(btn){
                                    btn.disabled = false;
                                    btn.innerHTML = orig;
                                }

                                if(resp && resp.status === 'ok'){

                                    Swal.fire({
                                        title: 'Saved',
                                        text: resp.message || 'Override applied successfully',
                                        icon: 'success'
                                    });

                                } else if(resp && resp.status === 'conflicts'){

                                    Swal.fire({
                                        title: 'Still Conflicts',
                                        html: '<pre style="text-align:left">'+JSON.stringify(resp.conflicts,null,2)+'</pre>',
                                        icon: 'warning'
                                    });

                                } else {

                                    Swal.fire({
                                        title: 'Server Response',
                                        text: JSON.stringify(resp),
                                        icon: 'info'
                                    });

                                }

                                console.log('Override response:', resp);
                            },

                            error: function(err){

                                if(btn){
                                    btn.disabled = false;
                                    btn.innerHTML = orig;
                                }

                                Swal.fire({
                                    title: 'Error',
                                    text: 'Failed to send override',
                                    icon: 'error'
                                });

                                console.error('Override error:', err);
                            }
                        });

                    }

                });

            }

            else if(response && response.status === 'ok'){

                Swal.fire({
                    title: 'Success',
                    text: response.message || 'Timetable updated',
                    icon: 'success'
                });

            }

            else{

                Swal.fire({
                    title: 'Server Response',
                    text: JSON.stringify(response),
                    icon: 'info'
                });

            }

        }
        catch(e){

            console.error('Response parsing error:', e);

            Swal.fire({
                title: 'Error',
                text: 'Failed to parse server response',
                icon: 'error'
            });

        }

        showToast('Timetable sent to fixtt.php', 'success');
        console.log('fixtt.php response:', response);
    },

    error: function(err){

        if(btn){
            btn.disabled = false;
            btn.innerHTML = orig;
        }

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

function renderTimetable(data){
    const days = ["Mon","Tue","Wed","Thu","Fri"];
    const daysFull = ["Monday","Tuesday","Wednesday","Thursday","Friday"];
    const colorMap = buildColorMap(data);

    let html = `
    <div class="schedule-section">
        <div class="schedule-header">
            <h2>Weekly Schedule</h2>
            <i class="fa-solid fa-pen schedule-header .edit-icon" style="color:#9ca3af;cursor:pointer;font-size:16px;" title="Edit"></i>
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
    $('#timetable-display').html(html);
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

</script>
</body>
</html>