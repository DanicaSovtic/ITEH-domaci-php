<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Stanovnici</title>
</head>

<body>
    <?php
    include "navigacija.php";
    ?>
    <div class='container mt-2'>
        <h1 class='text-center'>Stanovnistvo</h1>
        <div>
            <input type="text" id='pretraga' class="form-control" placeholder="Pretrazi...">
        </div>
        <div>

            <table class="table table-light">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ime</th>
                        <th>Prezime</th>
                        <th>Datum rodjenja</th>
                        <th>Pol</th>
                        <th>Drzavljanstvo</th>
                        <th>Nacionalnost</th>
                        <th>Grad</th>
                        <th>Akcije</th>
                    </tr>

                </thead>
                <tbody id='podaci'>

                </tbody>
            </table>
        </div>
        <div>
            <h2 id='formaNaslov'>Kreiraj osobu</h2>
            <form id='forma'>
                <div class="form-group">
                    <label for="ime">Ime</label>
                    <input required type="text" class="form-control" id="ime" placeholder="Ime">
                </div>
                <div class="form-group">
                    <label for="prezime">Prezime</label>
                    <input required type="text" class="form-control" id="prezime" placeholder="Prezime">
                </div>
                <div class="form-group">
                    <label for="datumRodjenja">Datum rodjenja</label>
                    <input required type="date" class="form-control" id="datumRodjenja" placeholder="Datum rodjenja">
                </div>
                <div class="form-group">
                    <label for="pol">Pol</label>
                    <select id="pol" class="form-control">
                        <option value="M">Muski</option>
                        <option value="Z">Zenski</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="drzavljanstvo">Drzavljanstvo</label>
                    <input required type="text" class="form-control" id="drzavljanstvo" placeholder="Drzavljanstvo">
                </div>
                <div class="form-group">
                    <label for="nacionalnost">Nacionalnost</label>
                    <input required type="text" class="form-control" id="nacionalnost" placeholder="Nacionalnost">
                </div>
                <div class="form-group">
                    <label for="grad">Grad</label>
                    <select id="grad" class="form-control">
                    </select>
                </div>
                <button type="submit" class="btn btn-primary form-control mt-3">Sacuvaj</button>
            </form>
            <button id='vrati' hidden class="btn btn-secondary form-control mt-3">Vrati se nazad</button>

        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        let osobe = [];
        let selId = 0;
        $(document).ready(function () {
            ucitajGradove();
            ucitajOsobe();
            $('#forma').submit(e => {
                e.preventDefault();
                const ime = $('#ime').val();
                const prezime = $('#prezime').val();
                const datumRodjenja = $('#datumRodjenja').val();
                const pol = $('#pol').val();
                const drzavljanstvo = $('#drzavljanstvo').val();
                const nacionalnost = $('#nacionalnost').val();
                const grad = $('#grad').val();
                $.post('./servis/osobaServis.php', {
                    metoda: selId ? 'izmeni' : 'kreiraj',
                    ime,
                    prezime,
                    pol,
                    drzavljanstvo,
                    nacionalnost,
                    grad_id: grad,
                    datum_rodjenja: datumRodjenja,
                    id: selId
                }).then(res => {
                    res = JSON.parse(res);
                    if (!res.status) {
                        alert(res.error);
                        return
                    }
                    ucitajOsobe();
                })
            })
            $('#pretraga').change(() => {
                popuniTabelu();
            })
            $('#vrati').click(() => {
                popuniFormu(0);
            })
        })

        function ucitajOsobe() {
            $.getJSON('./servis/osobaServis.php', { metoda: 'sve' }).then(res => {
                if (!res.status) {
                    alert(res.error);
                    return;
                }
                osobe = res.podaci;
                popuniTabelu()
            })
        }
        function popuniTabelu() {
            let pretraga = $('#pretraga').val();
            $("#podaci").html('');
            const filtrirani = osobe.filter(element => {
                return element.ime.toLowerCase().includes(pretraga.toLowerCase())
                    || element.prezime.toLowerCase().includes(pretraga.toLowerCase())
                    || element.drzavljanstvo.toLowerCase().includes(pretraga.toLowerCase())
                    || element.nacionalnost.toLowerCase().includes(pretraga.toLowerCase())
            })
            for (let osoba of filtrirani) {
                $("#podaci").append(`
                <tr>
                    <td class="align-middle">${osoba.id}</td>
                        <td class="align-middle">${osoba.ime}</td>
                        <td class="align-middle">${osoba.prezime}</td>
                        <td class="align-middle">${getDatum(osoba.datum_rodjenja)}</td>
                        <td class="align-middle">${osoba.pol}</td>
                        <td class="align-middle">${osoba.drzavljanstvo}</td>
                        <td class="align-middle">${osoba.nacionalnost}</td>
                        <td class="align-middle">${osoba.grad}</td>
                        <td class="align-middle">
                           <div>
                            <button onClick="popuniFormu(${osoba.id})" class='btn btn-success w-100'>Izmeni</button>   
                            </div> 
                            <div class='mt-2'>
                            <button onClick="obrisiOsobu(${osoba.id})" class='btn btn-danger w-100'>Obrisi</button> 
                        </div>    
                        </td>
                    </tr>
                `)
            }
        }
        function getDatum(datum) {
            const date = new Date(datum);
            return date.getDate() + '.' + (date.getMonth() + 1) + '.' + date.getFullYear();
        }
        function obrisiOsobu(id) {
            $.post('./servis/osobaServis.php', { metoda: 'obrisi', id }).then(res => {
                res = JSON.parse(res);
                if (!res.status) {
                    alert(res.error);
                    return;
                }
                osobe = osobe.filter(e => e.id != id);
                popuniFormu(0);
                popuniTabelu();
            })
        }
        function ucitajGradove() {
            $.getJSON('./servis/gradServis.php', { metoda: 'sve' }).then(res => {
                if (!res.status) {
                    alert(res.error);
                    return;
                }
                const gradovi = res.podaci;
                for (let grad of gradovi) {
                    $('#grad').append(`
                    <option value='${grad.id}'>${grad.naziv}</option>
                   `)
                }
            })
        }

        function popuniFormu(id) {
            selId = id;
            const osoba = osobe.find(e => e.id == id);
            $('#ime').val(osoba?.ime || '');
            $('#prezime').val(osoba?.prezime || '');
            $('#pol').val(osoba?.pol || 'M');
            $('#drzavljanstvo').val(osoba?.prezime || '');
            $('#datumRodjenja').val(osoba?.datum_rodjenja.substring(0, 10) || '');
            $('#nacionalnost').val(osoba?.nacionalnost || '');
            $('#grad').val(osoba?.grad_id || 0);
            $('#vrati').attr('hidden', osoba === undefined);
            $('#formaNaslov').html(osoba ? 'Izmeni osobu' : 'Kreiraj osobu');
        }
    </script>
</body>

</html>