<!DOCTYPE html>
<html>
<head>
    <title>Timetable Generator</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial;
            background: #f4f6f9;
            padding: 20px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        input, select {
            padding: 8px;
            margin: 5px;
            width: 180px;
        }
        button {
            padding: 8px 15px;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .remove-btn {
            background: red;
        }
    </style>
</head>
<body>

<h2>Timetable Input Form</h2>

<div class="card">
    <input type="text" id="class" placeholder="Class (III IT A)">
    <input type="text" id="dept" placeholder="Department">
    <input type="number" id="year" placeholder="Year">
    <input type="number" id="sem" placeholder="Semester">
    <input type="text" id="academic_year" placeholder="Academic Year">
</div>

<h3>Subjects</h3>

<div id="subjects-container"></div>

<button onclick="addSubject()">+ Add Subject</button>
<br><br>
<button onclick="submitForm()">Generate Timetable</button>
    <br><br>
    <div id="timetable-display"></div>
<script>
let staffList = [];
let timetableData = {};

// Fetch staff from DB
$(document).ready(function(){
    $.get("get_staff.php", function(data){
        staffList = JSON.parse(data);
        addSubject(); // Add one subject row by default
    });
});

function addSubject(){
    let options = '';
    staffList.forEach(staff => {
        options += `<option value="${staff.id}">
            ${staff.name} (${staff.dept})
        </option>`;
    });

    let html = `
        <div class="card subject-row">
            <select class="staff_id">
                ${options}
            </select>
            <input type="text" class="subject" placeholder="Subject Name">
            <input type="number" class="periods" placeholder="Periods">
            <select class="type">
                <option value="theory">Theory</option>
                <option value="lab">Lab</option>
            </select>
            <button class="remove-btn" onclick="removeSubject(this)">Remove</button>
        </div>
    `;

    $("#subjects-container").append(html);
}

function removeSubject(btn){
    $(btn).closest(".subject-row").remove();
}

function submitForm(){
    let subjects = [];

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

    $.ajax({
        url: "api.php",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify(payload),
        success: function(response){
            alert("Success!");
            console.log(response);
            timetableData = response.timetable;
            renderTimetable(response.timetable);
        },
        error: function(err){
            alert("Error occurred");
            console.log(err);
        }
    });
}

// Render timetable object into HTML table
function renderTimetable(data){
    const days = ["Mon","Tue","Wed","Thu","Fri"];
    let html = '<table border="1" cellpadding="5" cellspacing="0"><thead><tr><th>Hour</th>';
    days.forEach(d=> html += `<th>${d}</th>`);
    html += '</tr></thead><tbody>';

    for(let hour=1; hour<=7; hour++){
        html += `<tr><td>${hour}</td>`;
        days.forEach(d=>{
            let cell = '';
            if(data[d] && data[d][hour]){
                const entry = data[d][hour];
                if(entry.subject){
                    const staff = staffList.find(s=>s.id==entry.staff_id);
                    const name = staff?staff.name:'Unknown';
                    cell = `<strong>${entry.subject}</strong><br>${name}`;
                }
            }
            html += `<td>${cell}</td>`;
        });
        html += '</tr>';
    }
    html += '</tbody></table>';
    $('#timetable-display').html(html);
}

</script>

</body>
</html>