<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Gradovi</title>
</head>

<body>
    <?php
    include "navigacija.php";
    ?>
    <div class="container mt-2">
        <h1 class="text-center">
            Gradovi u Srbiji
        </h1>
        <div class="m-2">
            <input type="text" id='pretraga' class="form-control" placeholder="Pretrazi...">
        </div>
        <div class="row">
            <div class="col-6">
                <table class="table table-light">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Naziv grada</th>
                            <th>Postanski broj</th>
                            <th>Akcije</th>
                        </tr>
                    </thead>
                    <tbody id='podaci'>

                    </tbody>
                </table>
            </div>
            <div class="col-1">

            </div>
            <div class="col-5">
                <h2 id='formaNaslov'>Kreiraj grad</h2>
                <form id='forma'>
                    <div class="form-group">
                        <label for="naziv">Naziv</label>
                        <input required type="text" class="form-control" id="naziv" placeholder="Naziv">
                    </div>
                    <div class="form-group">
                        <label for="postanskiBroj">Postanski broj</label>
                        <input required type="text" class="form-control" id="postanskiBroj"
                            placeholder="Postanski broj">
                    </div>
                    <button type="submit" class="btn btn-primary form-control mt-3">Sacuvaj</button>
                </form>
                <button id='vrati' hidden class="btn btn-secondary form-control mt-3">Vrati se naziv</button>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        let gradovi = [];
        let selId = 0;
        $(document).ready(function () {
            ucitajGradove();
            $('#forma').submit(e => {
                console.log('submit');
                e.preventDefault();
                const naziv = $('#naziv').val();
                const postanskiBroj = $('#postanskiBroj').val();
                $.post('./servis/gradServis.php', {
                    metoda: selId ? 'izmeni' : 'kreiraj',
                    naziv,
                    postanski_broj: postanskiBroj,
                    id: selId
                }).then(res => {
                    res = JSON.parse(res);
                    if (!res.status) {
                        alert(res.error);
                        return
                    }
                    ucitajGradove();
                })
            })
            $('#pretraga').change(() => {
                popuniTabelu();
            })
            $('#vrati').click(() => {
                popuniFormu(0);
            })

        });
        function ucitajGradove() {
            $.getJSON('./servis/gradServis.php', { metoda: 'sve' }).then(res => {
                if (!res.status) {
                    alert(res.error);
                    return;
                }
                gradovi = res.podaci;
                popuniTabelu();
            })
        }
        function popuniTabelu() {
            let pretraga = $('#pretraga').val();
            $("#podaci").html('');

            const filtrirani = gradovi.filter(element => {
                return element.naziv.toLowerCase().includes(pretraga.toLowerCase())
            })
            for (let grad of filtrirani) {
                $("#podaci").append(`
                    <tr>
                        <td>${grad.id}</td>
                        <td>${grad.naziv}</td>
                        <td>${grad.postanski_broj}</td>
                        <td>
                            <button onClick="popuniFormu(${grad.id})" class='btn btn-success'>Izmeni</button>    
                            <button onClick="obrisiGrad(${grad.id})" class='btn btn-danger'>Obrisi</button>    
                        </td>
                    </tr>
                `)
            }
        }
        function obrisiGrad(id) {
            $.post('./servis/gradServis.php', { metoda: 'obrisi', id }).then(res => {
                res = JSON.parse(res);
                if (!res.status) {
                    alert(res.error);
                    return;
                }
                gradovi = gradovi.filter(e => e.id != id);
                selId = 0;
                popuniTabelu();
            })
        }
        function popuniFormu(id) {
            selId = id;
            const grad = gradovi.find(e => e.id == id);
            $('#naziv').val(grad?.naziv || '');
            $('#postanskiBroj').val(grad?.postanski_broj || '');
            $('#vrati').attr('hidden', grad === undefined);
            $('#formaNaslov').html(grad ? 'Izmeni grad' : 'Kreiraj grad');
        }
    </script>
</body>

</html>