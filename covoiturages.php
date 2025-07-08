<body>
    <?php
    require_once 'html/header.html'
    ?>

    <section>
        <div class = "containerResult">
            <div class = "triResult">
                <h3> TRIER PAR : </h3>
                <h3> <a href = ""> Tout effacer </a>
            </div>

            <div class = "trajetResult">
                <div class = trajet>
                    <h3> Date, Ville de départ -> Ville d'arrivée </h3>
                    <h3> x trajets disponibles </h3>
                </div>
                <div class="linkResult">
                    <a href = "">
                        <span class = "detailsTrajet">
                            <span class ="horaires"> 21:30 o----- 1h20 ----o 22:50 <br> Cannes Toulon </span>
                            <span class ="prixTrajet"> 8.99€ </span>
                        </span>
                        <hr>
                        <span class = "chauffeur"> GINETTE ----- * 4.7 </span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php
    require_once 'html/footer.html'
    ?>

</body>