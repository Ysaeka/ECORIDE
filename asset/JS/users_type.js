document.addEventListener('DOMContentLoaded', () => {
    const chauffeurRadio = document.getElementById('chauffeur');
    const passagerRadio = document.getElementById('passager');
    const deuxRadio = document.getElementById('deux');
    const menuChauffeur = document.getElementById('menuChauffeur');
    const typeDiv = document.querySelector('.type');

    let typeUtilisateur = typeDiv.dataset.userType;

    const afficherMenu = (actif) => {
        menuChauffeur.style.display = actif ? 'block' : 'none';
    };

    const initialiserRadios = (type) => {
        switch(type) {
            case 'chauffeur':
                chauffeurRadio.checked = true;
                afficherMenu(true);
                break;
            case 'les_deux':
                deuxRadio.checked = true;
                afficherMenu(true);
                break;
            default:
                passagerRadio.checked = true;
                afficherMenu(false);
        }
    };

    initialiserRadios(typeUtilisateur);

    const envoyerType = async (type) => {
        try {
            const form = new FormData();
            form.append('type', type);
            const resp = await fetch('script/update_type.php', { method: 'POST', body: form });
            const data = await resp.json();

            if (data.success) {
                console.log('Type mis à jour');
                typeDiv.dataset.userType = type;
                initialiserRadios(type);
            } else {
                console.error(`Erreur : ${data.error}`);
                initialiserRadios(typeDiv.dataset.userType);
            }
        } catch (err) {
            console.error('Erreur fetch :', err);
            initialiserRadios(typeDiv.dataset.userType);
        }
    };

    document.querySelectorAll('input[name="type"]').forEach(radio => {
        radio.addEventListener('change', () => {
            const nouveauType = radio.value;
            const estChauffeur = nouveauType === 'chauffeur' || nouveauType === 'les_deux';
            afficherMenu(estChauffeur);
            envoyerType(nouveauType);
        });
    });
});
