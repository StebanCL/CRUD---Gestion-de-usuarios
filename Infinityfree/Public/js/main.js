// Variable global para almacenar los datos y evitar problemas de comillas en el HTML
let clientesLocales = [];

document.addEventListener('DOMContentLoaded', () => {
    loadClients();
    setupFormListener();
});

// 1. Cargar la lista de clientes
async function loadClients() {
    try {
        const res = await fetch('Services/ClientControler.php?action=list');
        const data = await res.json();
        
        clientesLocales = data; 
        
        const tbody = document.querySelector('#clientesTable tbody');
        if (!tbody) return; 
        
        tbody.innerHTML = '';
        data.forEach(c => {
            // Se añade "data-label" a cada celda para el diseño responsivo
            tbody.innerHTML += `
                <tr>
                    <td data-label="ID">${c.ID}</td>
                    <td data-label="Nombre">${c.NOMBRE}</td>
                    <td data-label="Teléfono">${c.TELEFONO}</td>
                    <td data-label="Correo">${c.CORREO}</td>
                    <td data-label="Acciones">
                        <button class="btn-edit" onclick="prepareEditById(${c.ID})">Editar</button>
                        <button class="btn-delete" onclick="deleteClient(${c.ID})">Eliminar</button>
                    </td>
                </tr>`;
        });
    } catch (e) { 
        console.error("Error al cargar:", e); 
    }
}

// 2. Eliminar cliente
window.deleteClient = async function(id) { 
    if (confirm('¿Estás seguro de que deseas eliminar este cliente?')) {
        try {
            const res = await fetch(`Services/ClientControler.php?action=delete&id=${id}`);
            const result = await res.json();
            if (result.success) {
                loadClients();
            }
        } catch (e) {
            console.error("Error al eliminar:", e);
        }
    }
}

// 3. Preparar el formulario para editar
window.prepareEditById = function(id) {
    const cliente = clientesLocales.find(c => c.ID == id);
    
    if (cliente) {
        document.getElementById('clientId').value = cliente.ID;
        document.getElementById('nombre').value = cliente.NOMBRE;
        document.getElementById('telefono').value = cliente.TELEFONO;
        document.getElementById('correo').value = cliente.CORREO;
        
        document.getElementById('btnSubmit').innerText = "Actualizar Cliente";
        
        const btnCancel = document.getElementById('btnCancel');
        if(btnCancel) btnCancel.style.display = 'inline-block';
        
        // Hacer scroll hacia arriba para que el usuario vea el formulario
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
};

// 4. Configurar el listener del formulario
function setupFormListener() {
    const form = document.getElementById('clientForm');
    if (!form) return;

    form.onsubmit = async (e) => {
        e.preventDefault();
        const id = document.getElementById('clientId').value;
        const action = id ? 'update' : 'add';
        const formData = new FormData(form);

        try {
            const res = await fetch(`Services/ClientControler.php?action=${action}`, {
                method: 'POST',
                body: formData
            });
            
            const result = await res.json();
            
            if (result.success) {
                window.resetForm(); 
                loadClients();
            } else {
                alert("Error al procesar la solicitud");
            }
        } catch (e) {
            console.error("Error al procesar formulario:", e);
        }
    };
}

// 5. Función global para limpiar el formulario
window.resetForm = function() {
    const form = document.getElementById('clientForm');
    if(form) form.reset();
    
    const idInput = document.getElementById('clientId');
    if(idInput) idInput.value = '';
    
    document.getElementById('btnSubmit').innerText = "Guardar Cliente";
    
    const btnCancel = document.getElementById('btnCancel');
    if(btnCancel) btnCancel.style.display = 'none';
}