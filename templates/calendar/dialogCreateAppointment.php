<div class="dialog-parent invisible" id="dialog-create-appointment">
    <div class="dialog card pt-5 shadow-sm">
        <h1 class="text-center mb-3">
            Create Appointment
        </h1>
        <?php
        $formAction = "/appointment/create";
        $submitButtonText = "Create";
        $type = "create";
        require "dialogCreateEditAppointmentBody.php";
        ?>
    </div>
</div>