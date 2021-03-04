<?php
const COLUMNS = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");

function index_to_time($index)
{
    return str_pad($index, 2, '0', STR_PAD_LEFT) . ":00";
}

?>

<div class="container mw-100">
    <div class="row pb-5">
        <div class="col">
            <img src="/images/logo.png"
                 class="card-img-top"
                 alt="The Mondate Logo"
                 style="width: 100px">
        </div>
        <div class="col">
            <button class="btn btn-primary w-50">Logout</button>
        </div>
    </div>
    <div class="row">
        <div class="container col px-5">
            <div class="row">
                <button class="btn btn-primary w-100">Create Appointment</button>
            </div>
            <div class="row">
                <button class="btn btn-primary w-100">Edit Appointment</button>
            </div>
            <div class="row">
                <button class="btn btn-primary w-100">Delete Appointment</button>
            </div>
        </div>
        <div class="col-10">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col"></th>
                    <?php
                    foreach (COLUMNS as $column) {
                        echo "<th scope=\"col\" class=\"text-center\">$column</th>";
                    }
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                for ($i = 0; $i < 24; $i++) {
                    echo "<tr>";
                    echo "<th scope=\"row\" class=\"p-0 align-middle\">" . index_to_time($i) . "</th>";

                    for ($j = 0; $j < sizeof(COLUMNS); $j++) {
                        if ($i == 17 && $j == 3) {
                            echo "
                            <td class=\"cell-appointment p-0 align-middle\">
                                <button class=\"btn btn-primary m-0 w-100\">Dentist</button>
                            </td>";
                        } else if ($i == 3 && $j == 2) {
                            echo "
                            <td class=\"cell-appointment p-0 align-middle\">
                                <button class=\"btn btn-primary m-0 w-100\">Bowling</button>
                            </td>";
                        } else {
                            echo "<td class=\"cell-appointment\"></td>";
                        }
                    }

                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <button>Last</button>
            <span>03.07.2021 - 09.07.2021</span>
            <button>Next</button>
        </div>
    </div>
</div>

<script>
    tableBody = document.querySelector('tbody')
    tableBody.scrollTop = 8 * 50;
</script>