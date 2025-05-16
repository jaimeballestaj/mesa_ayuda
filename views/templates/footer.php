
</main>

<!-- Footer profesional con diseño moderno -->
<footer class="footer mt-5" style="background: linear-gradient(135deg, #1e293b, #334155);">
    <div class="footer-bottom" style="background-color: rgba(0, 0, 0, 0.2); border-top: 1px solid rgba(255, 255, 255, 0.1);">
        <div class="container py-3">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 text-white-50">
                        &copy; <?php echo date("Y"); ?> Mesa de Ayuda. Todos los derechos reservados.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-white-50 text-decoration-none me-3 footer-bottom-link">Política de Privacidad</a>
                    <a href="#" class="text-white-50 text-decoration-none me-3 footer-bottom-link">Términos de Uso</a>
                    <a href="#" class="text-white-50 text-decoration-none footer-bottom-link">Cookies</a>
                </div>
            </div>
        </div>
  </footer>

<!-- Botón de volver arriba -->
<button id="btnScrollTop" class="btn btn-warning rounded-circle shadow-lg" style="position: fixed; bottom: 20px; right: 20px; display: none; width: 50px; height: 50px; z-index: 1000;">
    <i class="bi bi-arrow-up-short fs-4"></i>
</button>

<script src="/mesa_ayuda/assets/js/bootstrap.bundle.min.js"></script>

<script>
// Función para el botón de volver arriba
window.onscroll = function() {
    scrollFunction();
};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("btnScrollTop").style.display = "block";
    } else {
        document.getElementById("btnScrollTop").style.display = "none";
    }
}

document.getElementById("btnScrollTop").addEventListener("click", function() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});
</script>

<style>
/* Estilos adicionales para el footer */
.footer-link {
    transition: all 0.3s ease;
}

.footer-link:hover {
    color: white !important;
    padding-left: 5px;
}

.social-icon {
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.social-icon:hover {
    color: #fbbf24 !important;
    transform: translateY(-3px);
}

.footer-bottom-link {
    transition: all 0.3s ease;
}

.footer-bottom-link:hover {
    color: #fbbf24 !important;
}

#btnScrollTop {
    transition: all 0.3s ease;
}

#btnScrollTop:hover {
    transform: translateY(-5px);
    background: linear-gradient(135deg, #f59e0b, #dc2626) !important;
    border: none;
}

/* Animación para elementos del footer */
.footer {
    animation: fadeIn 0.8s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .footer .row > div {
        text-align: center;
    }
    
    .social-links {
        margin-top: 1rem;
    }
    
    .footer-bottom .col-md-6:last-child {
        margin-top: 1rem;
    }
}
</style>

</body>
</html>