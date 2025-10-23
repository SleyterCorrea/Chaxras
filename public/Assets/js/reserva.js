// reserva.js

// ––––– Variables globales –––––
let currentStep   = 'main-options';
let selectedDate  = null;
let selectedTime  = null;
let currentMonth  = new Date().getMonth();
let currentYear   = new Date().getFullYear();
let calendarOpen  = false;

// Meses en español
const months = [
    'Enero','Febrero','Marzo','Abril','Mayo','Junio',
    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'
];

// Slots cada 30m de 08:00 a 22:30
const timeSlots = Array.from({ length: ((21 - 8) * 2) }, (_, i) => {
    const h = 8 + Math.floor(i / 2);
    const m = (i % 2) * 30;
    return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;
});

// ——— Helpers de UI ———
function showStep(stepId) {
    document.querySelectorAll('.step-block').forEach(el => el.style.display = 'none');
    document.getElementById('progress-bar').style.display = (stepId !== 'main-options' ? 'block' : 'none');
    const block = document.getElementById(stepId);
    if (block) block.style.display = 'block';
    currentStep = stepId;
    updateProgressIndicator(stepId);
    scrollToTop();
}

function updateProgressIndicator(stepId) {
    const raw = stepId.replace(/^step-/, '');
    const steps = Array.from(document.querySelectorAll('.progress-step'));
    const idx   = steps.findIndex(el => el.dataset.step === raw);
    const pct   = idx >= 0 ? ((idx + 1) / steps.length) * 100 : 0;
    const line  = document.getElementById('progress-line');
    if (line) line.style.setProperty('--progress-fill', pct + '%');
    steps.forEach((el, i) => {
        el.classList.toggle('active',    i === idx);
        el.classList.toggle('completed', i < idx);
    });
}

function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ——— Pasos ———
function showRealizarReserva() { showStep('step-tipo'); }
function showVerReservas()      { showStep('ver-reservas'); }
function goBackToMain()         { showStep('main-options'); }

function toStep(step) {
    document.querySelectorAll('.step-block').forEach(el => el.style.display = 'none');
    document.getElementById('step-' + step).style.display = 'block';

    if (step === 'resumen') {
        updateSummary(); // Asegura que el resumen se actualice
    }

if (step === 'resumen') {
    updateSummary();
}

}

// ——— Calendario ———
function toggleCalendar() {
    calendarOpen = !calendarOpen;
    document.getElementById('calendar-popup').classList.toggle('active', calendarOpen);
}

function previousMonth() {
    currentMonth = currentMonth === 0 ? 11 : currentMonth - 1;
    if (currentMonth === 11) currentYear--;
    generateCalendar();
}

function nextMonth() {
    currentMonth = currentMonth === 11 ? 0 : currentMonth + 1;
    if (currentMonth === 0) currentYear++;
    generateCalendar();
}

function generateCalendar() {
    const grid  = document.getElementById('calendar-grid');
    const title = document.getElementById('current-month');
    if (!grid || !title) return;
    grid.innerHTML = '';
    title.textContent = `${months[currentMonth]} ${currentYear}`;

    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    for (let i = 0; i < firstDay; i++) {
        const d = document.createElement('div');
        d.className = 'calendar-day disabled';
        grid.appendChild(d);
    }

    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    const todayTS = new Date().setHours(0, 0, 0, 0);

    for (let d = 1; d <= daysInMonth; d++) {
        const cell = document.createElement('div');
        cell.className = 'calendar-day';
        cell.textContent = d;
        const thisDate = new Date(currentYear, currentMonth, d);

        if (thisDate.getTime() < todayTS) {
            cell.classList.add('disabled');
        } else {
            cell.addEventListener('click', () => selectDate(thisDate));
            if (selectedDate && thisDate.toDateString() === selectedDate.toDateString()) {
                cell.classList.add('selected');
            }
        }
        if (thisDate.toDateString() === new Date().toDateString()) {
            cell.classList.add('today');
        }
        grid.appendChild(cell);
    }
}

function selectDate(dt) {
    selectedDate = dt;
    document.getElementById('selected-date').textContent = dt.toLocaleDateString('es-ES', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });
    toggleCalendar();
    clearFieldError('date-input');
    fetchUnavailableSlots(dt);
}

function fetchUnavailableSlots(dateObj) {
    const iso = dateObj.toISOString().split('T')[0];
    const url = `${window.BASE_URL}reservas/disponibilidad?fecha=${iso}`;
    console.log('👉 Disponibilidad URL:', url);

    fetch(url)
        .then(r => r.ok ? r.json() : Promise.reject(`Status ${r.status}`))
        .then(data => generateTimeSlots(data))
        .catch(err => {
            console.warn('⚠ fetchUnavailableSlots error:', err);
            generateTimeSlots([]);
        });
}

function generateTimeSlots(unavailable = []) {
    const cont = document.getElementById('time-selector');
    cont.innerHTML = '';
    timeSlots.forEach(t => {
        const slot = document.createElement('div');
        slot.className = 'time-slot';
        slot.textContent = t;
        if (unavailable.includes(t)) {
            slot.classList.add('disabled');
        } else {
            slot.addEventListener('click', () => selectTime(t, slot));
        }
        cont.appendChild(slot);
    });
}

function selectTime(t, el) {
    selectedTime = t;
    document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
    el.classList.add('selected');
    clearFieldError('time-selector');
}

// ——— Personas ———
function increasePersonas() {
    const inp = document.getElementById('personas');
    const v   = parseInt(inp.value) || 1;
    if (v < parseInt(inp.max)) inp.value = v + 1;
}
function decreasePersonas() {
    const inp = document.getElementById('personas');
    const v   = parseInt(inp.value) || 1;
    if (v > parseInt(inp.min)) inp.value = v - 1;
}

// ——— Validación ———
function validateAndContinue() {
    const errs = [];
    if (!selectedDate)    errs.push({ field: 'date-input',    message: 'Selecciona una fecha' });
    if (!selectedTime)    errs.push({ field: 'time-selector', message: 'Selecciona una hora' });
    const p = document.getElementById('personas').value;
    if (!p || parseInt(p) < 1) errs.push({ field: 'personas', message: 'Número de personas inválido' });
    const tit = document.getElementById('titular').value.trim();
    if (!tit) errs.push({ field: 'titular', message: 'Ingresa el nombre del titular' });

    if (errs.length) {
        showErrors(errs);
        scrollToTop();
    } else {
        hideErrors();
        toStep('preferencias');
    }
}

function showErrors(errs) {
    const ec = document.getElementById('error-messages');
    ec.innerHTML = '';
    errs.forEach(e => {
        const d = document.createElement('div');
        d.className = 'error-message';
        d.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${e.message}`;
        ec.appendChild(d);
        if (e.field) {
            const fg = document.getElementById(e.field).closest('.form-group');
            fg && fg.classList.add('error');
        }
    });
    ec.style.display = 'block';
}

function hideErrors() {
    document.getElementById('error-messages').style.display = 'none';
    document.querySelectorAll('.form-group').forEach(g => g.classList.remove('error'));
}

function clearFieldError(id) {
    const grp = document.getElementById(id).closest('.form-group');
    grp && grp.classList.remove('error');
}

/// ——— Resumen ———
function updateSummary() {
    const cont = document.querySelector('.resumen-card');
    cont.innerHTML = '';

    const personas = document.getElementById('num_personas').value;
    const titular = document.getElementById('titular').value;
    const fecha = document.getElementById('fecha').value;
    const hora = document.getElementById('hora').value;
    const alergias = document.getElementById('alergias').value;
    const celebracion = document.getElementById('celebracion').value;
    const req = document.getElementById('requerimientos').value;

    const monto = (50 * parseInt(personas || 0)).toFixed(2);

    // Actualiza campos ocultos del formulario de pago
    document.getElementById('res-num-personas').value = personas;
    document.getElementById('res-titular').value = titular;
    document.getElementById('res-monto').value = monto;
    document.getElementById('res-fecha').value = fecha;
    document.getElementById('res-hora').value = hora;
    document.getElementById('res-alergias').value = alergias;
    document.getElementById('res-celebracion').value = celebracion;
    document.getElementById('res-req').value = req;

    // Mostrar resumen en HTML
    cont.innerHTML = `
        <ul>
            <li><strong>Titular:</strong> ${titular}</li>
            <li><strong>Personas:</strong> ${personas}</li>
            <li><strong>Fecha:</strong> ${fecha}</li>
            <li><strong>Hora:</strong> ${hora}</li>
            <li><strong>Alergias:</strong> ${alergias}</li>
            <li><strong>Celebración:</strong> ${celebracion}</li>
            <li><strong>Requerimientos:</strong> ${req}</li>
        </ul>
    `;

    document.getElementById('precio-estimado').innerText = monto;
}







// ——— Bootstrapping ———
document.addEventListener('DOMContentLoaded', () => {
    showStep('main-options');
    document.addEventListener('click', e => {
        const pop = document.getElementById('calendar-popup');
        const inp = document.getElementById('date-input');
        if (calendarOpen && pop && !pop.contains(e.target) && !inp.contains(e.target)) {
            toggleCalendar();
        }
    });
});
