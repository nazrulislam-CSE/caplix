
const sidebar = document.getElementById('sidebar');
const toggleBtn = document.getElementById('sidebarToggle');
const closeBtn = document.getElementById('sidebarClose');

// Toggle sidebar on all devices
toggleBtn.addEventListener('click', () => {
if (window.innerWidth >= 768) {
    // For larger screens, toggle the sidebar width
    if (sidebar.style.left === '0px') {
    sidebar.style.left = `-${sidebar.offsetWidth}px`;
    document.querySelector('.main').style.marginLeft = '0';
    } else {
    sidebar.style.left = '0';
    document.querySelector('.main').style.marginLeft = `${sidebar.offsetWidth}px`;
    }
} else {
    // For mobile, use the active class
    sidebar.classList.add('active');
}
});

// Close sidebar on mobile
closeBtn.addEventListener('click', () => {
sidebar.classList.remove('active');
});

// Submenu toggle
const submenuItems = document.querySelectorAll('.has-submenu > a');
submenuItems.forEach(item => {
item.addEventListener('click', e => {
    e.preventDefault();
    const parent = item.parentElement;
    parent.classList.toggle('show');
});
});

// Close sidebar when clicking outside on mobile
document.addEventListener('click', (e) => {
if (window.innerWidth < 768 && 
    !sidebar.contains(e.target) && 
    !toggleBtn.contains(e.target) &&
    sidebar.classList.contains('active')) {
    sidebar.classList.remove('active');
}
});

// chart js //
const ctx1 = document.getElementById('investmentChart').getContext('2d');
const investmentChart = new Chart(ctx1, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Investments',
            data: [12000, 15000, 14000, 18000, 20000, 22000],
            backgroundColor: 'rgba(67,97,238,0.2)',
            borderColor: 'rgba(67,97,238,1)',
            borderWidth: 2,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true }
        }
    }
});

const ctx2 = document.getElementById('projectChart').getContext('2d');
const projectChart = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: ['Project A', 'Project B', 'Project C', 'Project D'],
        datasets: [{
            label: 'Performance',
            data: [75, 50, 90, 60],
            backgroundColor: 'rgba(72,149,239,0.7)',
            borderColor: 'rgba(72,149,239,1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true }
        }
    }
});
