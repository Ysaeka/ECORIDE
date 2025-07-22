document.querySelectorAll('.btnModifier').forEach(button => {
    button.addEventListener('click', function (){
        const row = this.closest('tr');
        const id = row.dataset.id;

        const marque = row.querySelector('.marque');
        const modele = row.querySelector('.modele');
        const couleur = row.querySelector('.couleur');
        const immatriculation = row.querySelector('.immatriculation');
        const datePlaque = row.querySelector('.datePlaque');

        const oldValues = {
            marque: marque.innerText,
            modele: modele.innerText,
            couleur: couleur.innerText,
            immatriculation: immatriculation.innerText,
            datePlaque: datePlaque.innerText
        };

        marque.innerHTML = `<input type="text" name ="marque" value="${oldValues.marque}">`;
        modele.innerHTML = `<input type="text" name ="modele" value="${oldValues.modele}">`;
        couleur.innerHTML = `<input type="text" name ="couleur" value="${oldValues.couleur}">`;
        immatriculation.innerHTML = `<input type="text" name ="immatriculation" value="${oldValues.immatriculation}">`;
        datePlaque.innerHTML = `<input type="text" name ="datePlaque" value="${oldValues.datePlaque}">`;

        this.textContent = "Valider";
        this.classList.remove("btnModifier");
        this.classList.add("btnValider");

        const newButton = this.cloneNode(true);
        this.parentNode.replaceChild(newButton, this);

        
        newButton.addEventListener('click', function() {
            const newValues = {
                voiture_id: id,
                marque: row.querySelector('.marque input').value,
                modele: row.querySelector('.modele input').value,
                couleur: row.querySelector('.couleur input').value,
                immatriculation: row.querySelector('.immatriculation input').value,
                datePlaque: row.querySelector('.datePlaque input').value
            };

            const form = document.createElement('form');
            form.method = "POST";
            form.action = 'modif_voiture.php';

            for (let key in newValues) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = newValues[key];
                form.appendChild(input);  
            }
            
            document.body.appendChild(form);
            form.submit();
        });
    });
});


