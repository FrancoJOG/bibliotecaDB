<?php include 'header.php'; ?>

<!-- Banner Principal -->
<section class="bg-azul text-white text-center p-5">
    <div class="container">
        <h1 class="display-4">Bienvenido a la Biblioteca Virtual</h1>
        <p class="lead">Accede a un mundo de conocimiento desde la comodidad de tu hogar.</p>
        <a href="catalogo_libros.php" class="btn btn-light btn-lg mt-3">Explorar Catálogo</a>
    </div>
</section>

<!-- Sección Sobre Nosotros -->
<section class="p-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2>Sobre Nuestra Biblioteca</h2>
                <p class="lead">La Biblioteca Virtual te ofrece acceso a una amplia colección de libros, revistas y recursos digitales para tu desarrollo personal y académico.</p>
                <p>Con nuestra plataforma, puedes reservar libros, consultar disponibilidad y recibir recomendaciones personalizadas. Estamos comprometidos en fomentar el aprendizaje y el acceso libre al conocimiento.</p>
                
            </div>
            <div class="col-md-6">
                <img src="biblioteca.png" alt="Imagen de la Biblioteca" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Sección Servicios -->
<section class="bg-light p-5">
    <div class="container">
        <h2 class="text-center mb-4">Nuestros Servicios</h2>
        <div class="row text-center">
            <div class="col-md-4 bg-1 p-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title">Catálogo de Libros</h3>
                        <p class="card-text">Explora nuestra extensa colección de libros en diversas áreas de estudio. Encuentra el título que buscas y accede a recursos únicos.</p>
                        <a href="catalogo_libros.php" class="btn  bg-1">Ver Catálogo</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 bg-2 p-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title">Areas inovadoras</h3>
                        <p class="card-text">Asiste a nuestra biblioteca donde podras disfrutar de distintas areas para estudiar y leer, con conexion a internet.</p>
                        <a href="registro_usuarios.php" class="btn text-light bg-2">Registrarse</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 bg-3 p-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title">Préstamos y Reservas</h3>
                        <p class="card-text">Reserva tus libros favoritos y disfruta de una experiencia de préstamo sin complicaciones desde cualquier dispositivo.</p>
                        <a href="mis_prestamos_reservas.php" class="btn bg-3">Gestionar Préstamos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonios -->
<section class="p-5 text-white bg-secondary bg4">
    <div class="container">
        <h2 class="text-center mb-4">Lo Que Dicen Nuestros Usuarios</h2>
        <div class="row text-center">
            <div class="col-md-4">
                <blockquote class="blockquote">
                    <p class="mb-0">"La biblioteca ha transformado mi manera de aprender. Ahora tengo acceso a libros y recursos increíbles desde mi casa."</p>
                    <footer class="blockquote-footer text-white mt-3">Laura García</footer>
                </blockquote>
            </div>
            <div class="col-md-4">
                <blockquote class="blockquote">
                    <p class="mb-0">"Un lugar excelente para encontrar recursos académicos y complementar mis estudios universitarios."</p>
                    <footer class="blockquote-footer text-white mt-3">Miguel Santos</footer>
                </blockquote>
            </div>
            <div class="col-md-4">
                <blockquote class="blockquote">
                    <p class="mb-0">"Reservar y gestionar mis préstamos es muy fácil. Sin duda, una herramienta útil para cualquier estudiante."</p>
                    <footer class="blockquote-footer text-white mt-3">Ana Pérez</footer>
                </blockquote>
            </div>
        </div>
    </div>
</section>

<!-- Contacto -->
<section class="p-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Contáctanos</h2>
        <p class="text-center">¿Tienes alguna duda o comentario? Escríbenos y con gusto te atenderemos.</p>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="contacto.php" method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" required>
                    </div>
                    <div class="mb-3">
                        <label for="mensaje" class="form-label">Mensaje</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn text-light w-100 bg-2">Enviar Mensaje</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
