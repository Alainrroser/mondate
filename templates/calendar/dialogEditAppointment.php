<div class="dialog-parent invisible" id="dialog-edit-appointment">
    <div class="dialog card pt-5 shadow-sm">
        <h1 class="text-center mb-3">
            Edit Appointment
        </h1>
        <?php
        $formAction = "/appointment/edit";
        $submitButtonText = "Save";
        $type = "edit";
        require "dialogCreateEditAppointmentBody.php";
        ?>
    </div>
</div>