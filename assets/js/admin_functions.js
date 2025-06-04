document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-elimina').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const user = this.getAttribute('data-user');
            if(confirm("Sei sicuro di voler eliminare questo dipendente? L'operazione è irreversibile!")) {
                fetch('delete_employee.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'user=' + encodeURIComponent(user) + '&confirm=yes'
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        // Elimina la fila de la tabla sin recargar la página
                        const row = document.getElementById('row-' + user);
                        if(row) row.remove();
                        alert('Dipendente eliminato!');
                    } else {
                        alert('Errore: ' + (data.message || 'Impossibile eliminare il dipendente.'));
                    }
                })
                .catch(() => alert('Errore di rete.'));
            }
        });
    });
});