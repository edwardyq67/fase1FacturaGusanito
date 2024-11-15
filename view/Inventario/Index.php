<?php
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crear') {
    // Recibe los datos del formulario
    $isbn = !empty($_POST['segundoFormISBN']) ? trim($_POST['segundoFormISBN']) : null;
    $sku = !empty($_POST['segundoFormSKU']) ? trim($_POST['segundoFormSKU']) : null;
    $nombreProducto = !empty($_POST['NombProducto']) ? trim($_POST['NombProducto']) : null;
    $cantidadStock = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : null;
    $precio = isset($_POST['precio']) ? (float)$_POST['precio'] : null;

    // Verifica que al menos ISBN o SKU tengan contenido
    if (!$isbn && !$sku) {
        echo "Error: Debes proporcionar al menos ISBN o SKU.";
    } else {
        // Conexión a la base de datos ya gestionada por 'conexion.php'

        try {
            // SQL para insertar los datos en la base de datos
            $sql = "INSERT INTO inventario (isbn, sku, nombreProducto, cantidadStock, precio) 
                    VALUES (:isbn, :sku, :nombreProducto, :cantidadStock, :precio)";

            // Prepara la consulta
            $stmt = $conn->prepare($sql);

            // Asocia los parámetros con los valores
            $stmt->bindParam(':isbn', $isbn);
            $stmt->bindParam(':sku', $sku);
            $stmt->bindParam(':nombreProducto', $nombreProducto);
            $stmt->bindParam(':cantidadStock', $cantidadStock);
            $stmt->bindParam(':precio', $precio);

            // Ejecuta la consulta
            if ($stmt->execute()) {
                echo "Registro insertado con éxito.";
            } else {
                echo "Error al insertar el registro.";
            }
        } catch (PDOException $e) {
            // Muestra el error si ocurre algún problema
            echo "Error: " . $e->getMessage();
        }
    }
}

if (isset($_POST['id'])) {
    // Obtener los datos del formulario
    $id = $_POST['id'];  // ID del producto
    $isbn = $_POST['isbn'];  // ISBN del producto
    $sku = $_POST['sku'];  // SKU del producto
    $nombreProducto = $_POST['nombreProducto'];  // Nombre del producto
    $cantidadStock = $_POST['cantidadStock'];  // Cantidad en stock
    $precio = $_POST['precio'];  // Precio del producto

    // Consulta SQL para actualizar el producto
    $sql = "UPDATE inventario 
            SET isbn = :isbn, sku = :sku, nombreProducto = :nombreProducto, cantidadStock = :cantidadStock, precio = :precio
            WHERE id = :id";

    // Preparar la consulta y ejecutar con los datos recibidos
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':isbn', $isbn);
        $stmt->bindParam(':sku', $sku);
        $stmt->bindParam(':nombreProducto', $nombreProducto);
        $stmt->bindParam(':cantidadStock', $cantidadStock, PDO::PARAM_INT);
        $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

        // Redirigir a la página de inventario después de la actualización
        header("Location: index.php?page=inventario"); 
        exit();
    } catch (PDOException $e) {
        echo "Error al actualizar el producto: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $id = (int)$_POST['id'];

    try {
        $sql = "DELETE FROM inventario WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Registro eliminado con éxito.";
        } else {
            echo "Error al eliminar el registro.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

$sql = "SELECT * FROM inventario";
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
                class="min-w-full text-left text-sm font-light text-surface ">
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
                            Codigo ISBN
                        </th>
                        <th
                            scope="col"
                            class=" border-neutral-200 px-6 py-4 dark:border-white/10">
                            Codigo SKU
                        </th>
                        <th scope="col" class="px-6 py-4">Nom. Prducto</th>
                        <th scope="col" class="px-6 py-4">Cantidad</th>
                        <th scope="col" class="px-6 py-4">Precio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $libro): ?>
                        <tr class="border-neutral-200 dark:border-white/10">
                            <td class="whitespace-nowrap flex gap-2 border-neutral-200 px-6 py-4 font-medium dark:border-white/10">
                                <button
                                    id="openModalEditar<?php echo htmlspecialchars($libro['id']); ?>"
                                    class="py-1 px-3 rounded-md bg-yellow-600 text-white hover:bg-yellow-500 transition-all duration-200">
                                    Editar
                                </button>
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($libro['id']); ?>">
                                    <button type="submit" name="eliminar" class="py-1 px-3 rounded-md bg-red-600 text-white hover:bg-red-500 transition-all duration-200">Eliminar</button>
                                </form>
                            </td>
                            <td class="whitespace-nowrap border-neutral-200 px-6 py-4 dark:border-white/10">
                                <?php echo htmlspecialchars($libro['isbn']); ?>
                            </td>
                            <td class="whitespace-nowrap border-neutral-200 px-6 py-4 dark:border-white/10">
                                <?php echo htmlspecialchars($libro['sku']); ?>
                            </td>
                            <td class="whitespace-nowrap border-neutral-200 px-6 py-4 dark:border-white/10">
                                <?php echo htmlspecialchars($libro['nombreProducto']); ?>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <?php echo htmlspecialchars($libro['cantidadStock']); ?>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <?php echo htmlspecialchars($libro['precio']); ?>
                            </td>
                        </tr>

                        <!-- Modal de Edición -->
                        <div id="editar<?php echo htmlspecialchars($libro['id']); ?>" class="hidden fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center">
                            <div class="bg-white p-6 rounded-md shadow-lg">
                            <strong class="text-slate-600 text-2xl md:text-xl">Editar Cliente</strong>
                                <form  class="pt-4" method="POST">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($libro['id']); ?>">

                                    <!-- Campo para editar el Nombre del Producto -->
                                    <label for="nombreProducto" class="block mb-2">Nombre del Producto:</label>
                                    <input type="text" name="nombreProducto" value="<?php echo htmlspecialchars($libro['nombreProducto']); ?>" class="w-full mb-4 p-2 border rounded-md focus:outline-none focus:outline-none">

                                    <!-- Campo para editar el ISBN -->
                                    <label for="isbn" class="block mb-2">ISBN:</label>
                                    <input type="text" name="isbn" value="<?php echo htmlspecialchars($libro['isbn']); ?>" class="w-full mb-4 p-2 border rounded-md focus:outline-none focus:outline-none">

                                    <!-- Campo para editar el SKU -->
                                    <label for="sku" class="block mb-2">SKU:</label>
                                    <input type="text" name="sku" value="<?php echo htmlspecialchars($libro['sku']); ?>" class="w-full mb-4 p-2 border rounded-md focus:outline-none focus:outline-none">

                                    <!-- Campo para editar la cantidad en stock -->
                                    <label for="cantidadStock" class="block mb-2">Cantidad en Stock:</label>
                                    <input type="number" name="cantidadStock" value="<?php echo htmlspecialchars($libro['cantidadStock']); ?>" class="w-full mb-4 p-2 border rounded-md focus:outline-none focus:outline-none">

                                    <!-- Campo para editar el precio -->
                                    <label for="precio" class="block mb-2">Precio:</label>
                                    <input type="number" name="precio" value="<?php echo htmlspecialchars($libro['precio']); ?>" step="0.01" class="w-full mb-4 p-2 border rounded-md focus:outline-none focus:outline-none">

                                    <!-- Botón para guardar los cambios -->
                                    <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-500 transition-all duration-200 rounded-md">Guardar Cambios</button>
                                </form>

                                <!-- Botón para cerrar el modal -->
                                <button id="closeModalEditar<?php echo htmlspecialchars($libro['id']); ?>" class="mt-4 py-2 px-3 bg-red-600 text-white rounded-md hover:bg-red-500 transition-all duration-200">
                                    Cancelar
                                </button>
                            </div>

                        </div>

                        <div id="editar<?php echo htmlspecialchars($cliente['id']); ?>" class="fixed inset-0 hidden flex items-center justify-center bg-black bg-opacity-50 ">
                            <div class="bg-transparent w-[80%]  rounded-md p-4">

                                <button class="py-1 px-3 rounded-t-md bg-blue-600 text-white hover:bg-blue-500 transition-all duration-200">Editar Inventario</button>

                                <form id="formInfoCodigoEdit" method="POST" class="bg-white h-full p-4 hidden text-sm">
                                    <input type="hidden" name="cliente_id" value="<?php echo htmlspecialchars($cliente['id']); ?>">
                                    <div class="grid grid-cols-4 gap-4">
                                        <div class="flex flex-col col-span-2">
                                            <label for="editIsbn" class="font-bold mb-1">ISBN: </label>
                                            <input name="editIsbn" id="editIsbn" value="isbnInput" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3">
                                        </div>
                                        <div class="flex flex-col col-span-2">
                                            <label for="editSku" class="font-bold mb-1">SKU: </label>
                                            <input name="editSku" id="editSku" value="SKUInput" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3">
                                        </div>
                                        <div class="flex flex-col col-span-2">
                                            <label for="editNombreProducto" class="font-bold mb-1">Nombre del Producto: </label>
                                            <input name="editNombreProducto" id="editNombreProducto" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3">
                                        </div>
                                        <div class="flex flex-col col-span-1">
                                            <label for="editPrecio" class="font-bold mb-1">Precio: </label>
                                            <input name="editPrecio" id="editPrecio" type="number" step="0.01" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3">
                                        </div>

                                        <div class="flex flex-col col-span-1">
                                            <label for="editCantidadStock" class="font-bold mb-1">Cantidad: </label>
                                            <input name="editCantidadStock" id="editCantidadStock" type="number" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3">
                                        </div>
                                    </div>

                                    <div class="flex gap-4">
                                        <button type="submit" class="flex col-span-5 mt-4 py-1 px-3 bg-blue-600 text-white rounded-md hover:bg-blue-500 transition-all duration-200">
                                            Actualizar
                                        </button>
                                        <button id="closeModalEditar" class="flex col-span-5 mt-4 py-1 px-3 bg-red-600 text-white rounded-md hover:bg-red-500 transition-all duration-200">
                                            Cancelar
                                        </button>
                                    </div>
                                </form>
                            </div>
                            </>
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


<div id="crear" class="fixed inset-0 hidden flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-transparent w-[80%] rounded-md p-4">
        <button class="py-1 px-3 rounded-t-md bg-blue-600 text-white hover:bg-blue-500 transition-all duration-200">Inventario</button>

        <form id="formInfoCodigo" method="POST" class="bg-white h-full p-4 text-sm">
            <input type="hidden" name="accion" value="crear">
            <div class="grid grid-cols-4 gap-4">
                <!-- ISBN -->
                <div class="flex flex-col col-span-2">
                    <label for="segundoFormISBN" class="font-bold mb-1">ISBN: </label>
                    <input name="segundoFormISBN" maxlength="13" minlength="13" id="segundoFormISBN" type="text" class="w-full mb-4 p-2 border rounded-md focus:outline-none focus:outline-none focus:outline-none">
                </div>
                <!-- SKU -->
                <div class="flex flex-col col-span-2">
                    <label for="segundoFormSKU" class="font-bold mb-1">SKU: </label>
                    <input name="segundoFormSKU" id="segundoFormSKU" maxlength="14" minlength="14" type="text" class="w-full mb-4 p-2 border rounded-md focus:outline-none focus:outline-none focus:outline-none">
                </div>
                <!-- Nombre del Producto -->
                <div class="flex flex-col col-span-2">
                    <label for="NombProducto" class="font-bold mb-1">Nombre del Producto: </label>
                    <input name="NombProducto" id="NombProducto" type="text" class="w-full mb-4 p-2 border rounded-md focus:outline-none focus:outline-none focus:outline-none">
                </div>
                <!-- Precio -->
                <div class="flex flex-col col-span-1">
                    <label for="precio" class="font-bold mb-1">Precio: </label>
                    <input name="precio" id="precio" type="number" step="0.01" class="w-full mb-4 p-2 border rounded-md focus:outline-none focus:outline-none focus:outline-none">
                </div>
                <!-- Cantidad -->
                <div class="flex flex-col col-span-1">
                    <label for="cantidad" class="font-bold mb-1">Cantidad: </label>
                    <input name="cantidad" id="cantidad" type="number" class="w-full mb-4 p-2 border rounded-md focus:outline-none focus:outline-none">
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex col-span-5 mt-4 py-1 px-3 bg-blue-600 text-white rounded-md hover:bg-blue-500 transition-all duration-200">
                    Guardar
                </button>
                <button id="closeModal" class="flex col-span-5 mt-4 py-1 px-3 bg-red-600 text-white rounded-md hover:bg-red-500 transition-all duration-200">
                    Cancelar
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
    // Variables para los modales de creación
    const openModalCrear = document.getElementById('openModal');
    const closeModalCrear = document.getElementById('closeModal');
    const modalCrear = document.getElementById('crear');

    // Abrir modal de creación
    if (openModalCrear) {
        openModalCrear.addEventListener('click', () => {
            modalCrear.classList.remove('hidden');
        });
    }

    // Cerrar modal de creación
    if (closeModalCrear) {
        closeModalCrear.addEventListener('click', () => {
            modalCrear.classList.add('hidden');
        });
    }

    // Funciones para abrir y cerrar los modales de edición de manera dinámica
    document.querySelectorAll('[id^="openModalEditar"]').forEach(button => {
        button.addEventListener('click', (event) => {
            const libroId = event.target.id.replace('openModalEditar', ''); // Obtener el ID del libro
            const modalEditar = document.getElementById(`editar${libroId}`); // Obtener el modal correspondiente
            if (modalEditar) {
                modalEditar.classList.remove('hidden'); // Mostrar el modal de edición
            }
        });
    });

    document.querySelectorAll('[id^="closeModalEditar"]').forEach(button => {
        button.addEventListener('click', (event) => {
            const libroId = event.target.id.replace('closeModalEditar', ''); // Obtener el ID del libro
            const modalEditar = document.getElementById(`editar${libroId}`); // Obtener el modal correspondiente
            if (modalEditar) {
                modalEditar.classList.add('hidden'); // Ocultar el modal de edición
            }
        });
    });

    // Cerrar modales si se hace clic fuera de ellos
    window.addEventListener('click', (event) => {
        // Cerrar modal de creación si se hace clic fuera de él
        if (modalCrear && event.target === modalCrear) {
            modalCrear.classList.add('hidden');
        }

        // Cerrar los modales de edición si se hace clic fuera de ellos
        document.querySelectorAll('[id^="editar"]').forEach(modal => {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>