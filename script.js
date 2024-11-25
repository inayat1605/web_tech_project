// Toggle Password Visibility
function togglePassword() {
    const passwordField = document.getElementById('password');
    passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
}

// Chart.js Progress Chart
const ctx = document.getElementById('progressChart').getContext('2d');
const progressChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Completed', 'Pending'],
        datasets: [{
            data: [60, 40],
            backgroundColor: ['#4caf50', '#f44336'], // Green for completed, red for pending
        }],
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true },
        },
    },
});

// Logout Function
function logout() {
    alert('Logging out...');
    window.location.href = 'logout.php';
}

// Delete Task Function
function deleteTask(taskId) {
    fetch('delete_task.php', {
        method: 'POST',
        body: new URLSearchParams({ task_id: taskId }),
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        location.reload(); // Reload the page to update tasks
    });
}

// Dark Mode Toggle
const toggleDarkMode = () => {
    // Toggle the "dark-mode" class on the body
    document.body.classList.toggle('dark-mode');

    // Save the current mode to localStorage
    const isDarkMode = document.body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDarkMode);
};

// Event listener for button click
document.addEventListener('DOMContentLoaded', () => {
    // Check localStorage for the user's preference
    const darkModeEnabled = localStorage.getItem('darkMode') === 'true';
    if (darkModeEnabled) {
        document.body.classList.add('dark-mode');
    }

    // Attach the toggle function to the button
    const toggleButton = document.querySelector('.dark-mode-toggle');
    if (toggleButton) {
        toggleButton.addEventListener('click', toggleDarkMode);
    } else {
        console.error('Dark mode toggle button not found!');
    }
});

// Clock Logic
function updateClock() {
    const clockDiv = document.getElementById('clock');
    const now = new Date();
    clockDiv.textContent = now.toLocaleTimeString(); // Display current time
}
setInterval(updateClock, 1000); // Update every second

// Calendar Logic
const calendarDiv = document.getElementById('calendar');
const taskList = document.getElementById('task-list');

function generateCalendar(tasks) {
    const now = new Date();
    const year = now.getFullYear();
    const month = now.getMonth();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    let calendarHTML = `<h4>${now.toLocaleString('default', { month: 'long' })} ${year}</h4>`;
    calendarHTML += '<table><tr>';

    // Day Headers
    const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    days.forEach(day => (calendarHTML += `<th>${day}</th>`));
    calendarHTML += '</tr><tr>';

    // Blank Days at the Start
    const firstDay = new Date(year, month, 1).getDay();
    for (let i = 0; i < firstDay; i++) {
        calendarHTML += '<td></td>';
    }

    // Days with Task Highlights
    for (let day = 1; day <= daysInMonth; day++) {
        const currentDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const tasksOnDay = tasks.filter(task => task.deadline === currentDate);

        if ((firstDay + day - 1) % 7 === 0) {
            calendarHTML += '</tr><tr>';
        }

        if (tasksOnDay.length > 0) {
            calendarHTML += `<td style="background-color: #ffcccc;" title="${tasksOnDay.map(t => t.task_name).join(', ')}">${day}</td>`;
        } else {
            calendarHTML += `<td>${day}</td>`;
        }
    }

    calendarHTML += '</tr></table>';
    calendarDiv.innerHTML = calendarHTML;

    // Update Task List
    taskList.innerHTML = tasks
        .map(task => `<li>${task.task_name} - Deadline: ${task.deadline}</li>`)
        .join('');
}

// Fetch Tasks and Generate Calendar
fetch('fetch_tasks.php')
    .then(response => response.json())
    .then(tasks => {
        generateCalendar(tasks);
        showReminders(tasks);
    });

// Show Reminders for Today's Tasks
function showReminders(tasks) {
    const today = new Date().toISOString().split('T')[0];
    const reminders = tasks.filter(task => task.deadline === today);

    if (reminders.length > 0) {
        alert(`You have ${reminders.length} tasks due today!`);
    }
}

// Show Congratulations Message
function showCongratulations() {
    const congratsDiv = document.getElementById('congratulatory-message');
    congratsDiv.style.display = 'block';
    setTimeout(() => {
        congratsDiv.style.display = 'none';
    }, 5000); // Hide after 5 seconds
}

    