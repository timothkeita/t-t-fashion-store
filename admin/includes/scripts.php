<!-- Bootstrap -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Dashboard JS -->

<script src="../../assets/js/dashboard.js"></script>

<script>

/* ==========================================
SIDEBAR TOGGLE
========================================== */

const toggle=document.getElementById("menu-toggle");

if(toggle){

toggle.addEventListener("click",function(){

document.querySelector(".sidebar").classList.toggle("collapsed");

document.querySelector(".main-content").classList.toggle("expanded");

});

}

/* ==========================================
TOOLTIPS
========================================== */

const tooltipTriggerList=[].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));

tooltipTriggerList.map(function (tooltipTriggerEl){

return new bootstrap.Tooltip(tooltipTriggerEl);

});

/* ==========================================
AUTO CLOSE ALERTS
========================================== */

setTimeout(function(){

document.querySelectorAll(".alert").forEach(function(alert){

alert.style.transition="0.5s";

alert.style.opacity="0";

setTimeout(function(){

alert.remove();

},500);

});

},4000);

</script>