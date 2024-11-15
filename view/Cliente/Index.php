<?php
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inserción de un nuevo cliente (creación)
    if (isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['telefono']) && isset($_POST['correo'])) {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];

        $sql = "INSERT INTO cliente (nombre, apellido, cel, correo) VALUES (:nombre, :apellido, :telefono, :correo)";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al insertar el registro: " . $e->getMessage();
        }
    }

    // Eliminación de un cliente
    if (isset($_POST['eliminar']) && isset($_POST['cliente_id'])) {
        $cliente_id = $_POST['cliente_id'];

        $sql = "DELETE FROM cliente WHERE id = :cliente_id";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
            $stmt->execute();
            header("Location: index.php?page=cliente"); // Redirigir después de eliminar
            exit();
        } catch (PDOException $e) {
            echo "Error al eliminar el cliente: " . $e->getMessage();
        }
    }

    // Edición de un cliente (actualización)
    if (isset($_POST['editar']) && isset($_POST['cliente_id'])) {
        $cliente_id = $_POST['cliente_id'];
        $nombre = $_POST['Editnombre'];
        $apellido = $_POST['Editapellido'];
        $telefono = $_POST['Edittelefono'];
        $correo = $_POST['Editcorreo'];

        $sql = "UPDATE cliente SET nombre = :nombre, apellido = :apellido, cel = :telefono, correo = :correo WHERE id = :cliente_id";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
            $stmt->execute();
            header("Location: index.php?page=cliente"); // Redirigir después de editar
            exit();
        } catch (PDOException $e) {
            echo "Error al editar el cliente: " . $e->getMessage();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica si se está editando un cliente
    if (isset($_POST['cliente_id'])) {
        $cliente_id = $_POST['cliente_id'];
        $nombre = $_POST['Editnombre'];
        $apellido = $_POST['Editapellido'];
        $telefono = $_POST['Edittelefono'];
        $correo = $_POST['Editcorreo'];

        // Actualizar los datos del cliente en la base de datos
        $sql = "UPDATE cliente SET nombre = :nombre, apellido = :apellido, cel = :telefono, correo = :correo WHERE id = :cliente_id";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
            $stmt->execute();
            header("Location: index.php?page=cliente"); // Redirigir después de actualizar
            exit();
        } catch (PDOException $e) {
            echo "Error al actualizar el cliente: " . $e->getMessage();
        }
    }
}

// Consulta para obtener todos los clientes
$sql = "SELECT * FROM cliente";
$stmt = $conn->prepare($sql);
$stmt->execute();
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div id="contenido" class="flex flex-col gap-5">
    <section class="bg-slate-50 rounded-md py-4 px-4 flex gap-4 items-center">
        <button id="openModal" class="py-1 px-3 rounded-md bg-green-600 text-white hover:bg-green-500 transition-all duration-200">
            Crear
        </button>
        <form action="" class="relative flex items-center text-sm">
            <label for="Buscar" class="font-bold mr-2">Buscar: </label>
            <input id="Buscar" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3 ">
            <i class="fa-solid fa-magnifying-glass absolute right-0 top-1/2 transform -translate-y-1/2 cursor-pointer text-gray-500 hover:text-gray-800 transition-all duration-200 px-3"></i>
        </form>
        <form action="" class="flex gap-4 items-center text-sm">
            <div class="flex items-center">
                <label for="" class="font-bold mr-2">Desde:</label>
                <input type="date" class="bg-zinc-200 rounded-md py-1 px-2 focus:outline-none">
            </div>
            <div class="flex items-center">
                <label for="" class="font-bold mr-2">Hasta:</label>
                <input type="date" class="bg-zinc-200 rounded-md py-1 px-2 focus:outline-none">
            </div>
        </form>
    </section>
    <section>
        <div class="overflow-hidden bg-white rounded-md">
            <table
                class="min-w-full text-left text-sm font-light text-surface">
                <thead
                    class=" border-neutral-200 font-medium dark:border-white/10">
                    <tr>
                        <th
                            scope="col"
                            class=" border-neutral-200 px-6 py-4 dark:border-white/10">

                        </th>
                        <th
                            scope="col"
                            class=" border-neutral-200 px-6 py-4 dark:border-white/10">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-4">Apellido</th>
                        <th scope="col" class="px-6 py-4">Cel/telefono</th>
                        <th scope="col" class="px-6 py-4">Correo</th>
                        <th scope="col" class="px-6 py-4">F.Creacion</th>
                    </tr>
                </thead>
                <tbody>
                  <?php foreach ($resultados as $cliente): ?>
    <tr class="border-neutral-200 dark:border-white/10">
        <td class="whitespace-nowrap flex gap-2 border-neutral-200 px-6 py-4 font-medium dark:border-white/10">
            <button
                id="openModalEditar<?php echo htmlspecialchars($cliente['id']); ?>"
                onclick="editarEmpleado('<?php echo htmlspecialchars($cliente['id']); ?>', '<?php echo htmlspecialchars($cliente['nombre']); ?>', '<?php echo htmlspecialchars($cliente['apellido']); ?>', '<?php echo htmlspecialchars($cliente['cel']); ?>', '<?php echo htmlspecialchars($cliente['correo']); ?>')"
                class="py-1 px-3 rounded-md bg-yellow-600 text-white hover:bg-yellow-500 transition-all duration-200">
                Editar
            </button>
            <form action="" method="POST" style="display:inline;">
                <input type="hidden" name="cliente_id" value="<?php echo htmlspecialchars($cliente['id']); ?>">
                <button type="submit" name="eliminar" class="py-1 px-3 rounded-md bg-red-600 text-white hover:bg-red-500 transition-all duration-200">Eliminar</button>
            </form>
            <a href="?page=historialCliente" class="py-1 px-3 rounded-md bg-blue-600 text-white hover:bg-blue-500 transition-all duration-200">Pedido</a>
        </td>
        <td class="whitespace-nowrap border-neutral-200 px-6 py-4 dark:border-white/10"><?php echo htmlspecialchars($cliente['nombre']); ?></td>
        <td class="whitespace-nowrap border-neutral-200 px-6 py-4 dark:border-white/10"><?php echo htmlspecialchars($cliente['apellido']); ?></td>
        <td class="whitespace-nowrap px-6 py-4"><?php echo htmlspecialchars($cliente['cel']); ?></td>
        <td class="whitespace-nowrap px-6 py-4"><?php echo htmlspecialchars($cliente['correo']); ?></td>
        <td class="whitespace-nowrap px-6 py-4"><?php echo htmlspecialchars($cliente['fecha_registro']); ?></td>
    </tr>

    <!-- Modal de edición con ID único -->
    <div id="editar<?php echo htmlspecialchars($cliente['id']); ?>" class="fixed inset-0 hidden flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-transparent w-[80%] rounded-md p-4 bg-white">
            <strong class="text-slate-600 text-2xl md:text-xl">Editar Cliente</strong>
            <form method="POST" class=" h-full px-4 pt-2">
                <input type="hidden" name="cliente_id" value="<?php echo htmlspecialchars($cliente['id']); ?>">
                <div class="rounded-b-md rounded-tr-md text-sm grid grid-cols-5 gap-4 h-auto">
                    <div class="flex flex-col col-span-2">
                        <label for="Editnombre" class="font-bold mb-1">Nombre: </label>
                        <input id="Editnombre" name="Editnombre" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" value="<?php echo htmlspecialchars($cliente['nombre']); ?>" required>
                    </div>
                    <div class="flex flex-col col-span-2">
                        <label for="Editapellido" class="font-bold mb-1">Apellido: </label>
                        <input id="Editapellido" name="Editapellido" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" value="<?php echo htmlspecialchars($cliente['apellido']); ?>" required>
                    </div>
                    <div class="flex flex-col col-span-1">
                        <label for="Edittelefono" class="font-bold mb-1">Celular/Telefono: </label>
                        <input id="Edittelefono" name="Edittelefono" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" value="<?php echo htmlspecialchars($cliente['cel']); ?>" required>
                    </div>
                    <div class="flex flex-col col-span-2">
                        <label for="Editcorreo" class="font-bold mb-1">Correo: </label>
                        <input id="Editcorreo" name="Editcorreo" type="email" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" value="<?php echo htmlspecialchars($cliente['correo']); ?>" required>
                    </div>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="flex col-span-5 mt-4 py-1 px-3 bg-blue-600 text-white rounded-md hover:bg-blue-500 transition-all duration-200">
                        Actualizar
                    </button>
                    <button type="button" id="closeModalEditar<?php echo htmlspecialchars($cliente['id']); ?>" class="flex col-span-5 mt-4 py-1 px-3 bg-red-600 text-white rounded-md hover:bg-red-500 transition-all duration-200">
                        Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>

<?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
    <section>
        <div>
            <ul class="list-style-none flex">
                <li>
                    <a
                        class="pointer-events-none relative block rounded bg-transparent px-3 py-1.5 text-sm text-surface/50 transition duration-300 dark:text-neutral-400">Previous</a>
                </li>
                <li>
                    <a
                        class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-surface transition duration-300 hover:bg-neutral-100 focus:bg-neutral-100 focus:text-primary-700 focus:outline-none active:bg-neutral-100 active:text-primary-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700  dark:focus:text-primary-500 dark:active:bg-neutral-700 dark:active:text-primary-500"
                        href="#!">1</a>
                </li>
                <li aria-current="page">
                    <a
                        class="relative block rounded bg-primary-100 px-3 py-1.5 text-sm font-medium text-primary-700 transition duration-300 focus:outline-none dark:bg-slate-900 dark:text-primary-500"
                        href="#!">2
                        <span
                            class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">(current)</span>
                    </a>
                </li>
                <li>
                    <a
                        class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-surface transition duration-300 hover:bg-neutral-100 focus:bg-neutral-100 focus:text-primary-700 focus:outline-none active:bg-neutral-100 active:text-primary-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:focus:text-primary-500 dark:active:bg-neutral-700 dark:active:text-primary-500"
                        href="#!">3</a>
                </li>
                <li>
                    <a
                        class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-surface transition duration-300 hover:bg-neutral-100 focus:bg-neutral-100 focus:text-primary-700 focus:outline-none active:bg-neutral-100 active:text-primary-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:focus:text-primary-500 dark:active:bg-neutral-700 dark:active:text-primary-500"
                        href="#!">Next</a>
                </li>
            </ul>
        </div>
    </section>
</div>


<div id="crear" class="fixed inset-0 hidden flex items-center justify-center bg-black bg-opacity-50 ">
    <div class="bg-transparent w-[80%]  rounded-md p-4">

        <button class="py-1 px-3 rounded-t-md bg-blue-600 text-white hover:bg-blue-500 transition-all duration-200">Pedido Crear</button>

        <form method="POST" class="bg-white h-full p-4">
            <div class="rounded-b-md rounded-tr-md text-sm grid grid-cols-5 gap-4 h-auto">
                <div class="flex flex-col col-span-2">
                    <label for="nombre" class="font-bold mb-1">Nombre: </label>
                    <input id="nombre" name="nombre" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" required>
                </div>
                <div class="flex flex-col col-span-2">
                    <label for="apellido" class="font-bold mb-1">Apellido: </label>
                    <input id="apellido" name="apellido" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" required>
                </div>
                <div class="flex flex-col col-span-1">
                    <label for="telefono" class="font-bold mb-1">Celular/Telefono: </label>
                    <input id="telefono" name="telefono" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" required>
                </div>
                <div class="flex flex-col col-span-2">
                    <label for="correo" class="font-bold mb-1">Correo: </label>
                    <input id="correo" name="correo" type="email" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" required>
                </div>
            </div>
            <div class="flex gap-4">
                <button type="submit" class="flex col-span-5 mt-4 py-1 px-3 bg-blue-600 text-white rounded-md hover:bg-blue-500 transition-all duration-200">
                    Crear
                </button>
                <button type="button" id="closeModal" class="flex col-span-5 mt-4 py-1 px-3 bg-red-600 text-white rounded-md hover:bg-red-500 transition-all duration-200">
                    Cerrar
                </button>
            </div>
        </form>
    </div>
</div>
<div id="editar" class="fixed inset-0 hidden flex items-center justify-center bg-black bg-opacity-50 ">
    <div class="bg-transparent w-[80%]  rounded-md p-4">
        <button class="py-1 px-3 rounded-t-md bg-blue-600 text-white hover:bg-blue-500 transition-all duration-200">Pedido Editar</button>
        <form method="POST" class="bg-white h-full p-4">
            <div class="rounded-b-md rounded-tr-md text-sm grid grid-cols-5 gap-4 h-auto">
                <div class="flex flex-col col-span-2">
                    <label for="Editnombre" class="font-bold mb-1">Nombre: </label>
                    <input id="Editnombre" name="Editnombre" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" required>
                </div>
                <div class="flex flex-col col-span-2">
                    <label for="Editapellido" class="font-bold mb-1">Apellido: </label>
                    <input id="Editapellido" name="Editapellido" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" required>
                </div>
                <div class="flex flex-col col-span-1">
                    <label for="Edittelefono" class="font-bold mb-1">Celular/Telefono: </label>
                    <input id="Edittelefono" name="Edittelefono" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" required>
                </div>
                <div class="flex flex-col col-span-2">
                    <label for="Editcorreo" class="font-bold mb-1">Correo: </label>
                    <input id="Editcorreo" name="Editcorreo" type="email" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" required>
                </div>
            </div>
            <div class="flex gap-4">
                <button type="submit" class="flex col-span-5 mt-4 py-1 px-3 bg-blue-600 text-white rounded-md hover:bg-blue-500 transition-all duration-200">
                    Crear
                </button>
                <button type="button" id="closeModalEditar" class="flex col-span-5 mt-4 py-1 px-3 bg-red-600 text-white rounded-md hover:bg-red-500 transition-all duration-200">
                    Cerrar
                </button>
            </div>
        </form>

    </div>
</div>
<style>
    #contenido {
        display: grid;
        min-height: 88vh;
        grid-template-rows:
            auto 1fr auto;
    }
</style>

<script>
    // Variables para los modales
    const openModalCrear = document.getElementById('openModal');
    const closeModalCrear = document.getElementById('closeModal');
    const modalCrear = document.getElementById('crear');

    // Eventos para el modal de creación
    if (openModalCrear) {
        openModalCrear.addEventListener('click', () => {
            modalCrear.classList.remove('hidden');
        });
    }

    if (closeModalCrear) {
        closeModalCrear.addEventListener('click', () => {
            modalCrear.classList.add('hidden');
        });
    }

    // Funciones para abrir y cerrar los modales de edición de manera dinámica
    document.querySelectorAll('[id^="openModalEditar"]').forEach(button => {
        button.addEventListener('click', (event) => {
            const clienteId = event.target.id.replace('openModalEditar', ''); // Obtener el ID del cliente
            const modalEditar = document.getElementById(`editar${clienteId}`); // Obtener el modal correspondiente
            if (modalEditar) {
                modalEditar.classList.remove('hidden'); // Mostrar el modal correspondiente
            }
        });
    });

    document.querySelectorAll('[id^="closeModalEditar"]').forEach(button => {
        button.addEventListener('click', (event) => {
            const clienteId = event.target.id.replace('closeModalEditar', ''); // Obtener el ID del cliente
            const modalEditar = document.getElementById(`editar${clienteId}`); // Obtener el modal correspondiente
            if (modalEditar) {
                modalEditar.classList.add('hidden'); // Ocultar el modal correspondiente
            }
        });
    });

    // Cerrar modales si se hace clic fuera de ellos
    window.addEventListener('click', (event) => {
        // Cerrar modal de creación
        if (event.target === modalCrear) {
            modalCrear.classList.add('hidden');
        }

        // Cerrar los modales de edición
        document.querySelectorAll('[id^="editar"]').forEach(modal => {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>
<script>
    function editarEmpleado(id, nombre, apellido, telefono, correo) {

        document.getElementById('Editnombre').value = nombre;
        document.getElementById('Editapellido').value = apellido;
        document.getElementById('Edittelefono').value = telefono;
        document.getElementById('Editcorreo').value = correo;


        let hiddenIdInput = document.createElement('input');
        hiddenIdInput.type = 'hidden';
        hiddenIdInput.name = 'id'; // Este es el nombre que se enviará al backend
        hiddenIdInput.value = id; // Este es el valor del id que se enviará al backend
        document.querySelector('form').appendChild(hiddenIdInput);
    }
</script>

