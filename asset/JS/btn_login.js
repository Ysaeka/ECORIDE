        function toggleMenu() {
          const menu = document.getElementById('dropdownMenu');
          const arrow = document.querySelector('.arrow');
          const isOpen = menu.style.display === 'block';

          menu.style.display = isOpen ? 'none' : 'block';
          arrow.classList.toggle('rotate', !isOpen);
        }

        
        function toggleCarsList() {
          const carsForm = document.getElementById("dropdownCarsList");
          carsForm.classList.toggle("show");
        }

