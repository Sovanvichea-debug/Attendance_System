<?php
session_start();
require_once(realpath(__DIR__.'/../classes/actions.class.php'));
$actionClass = new Actions();
if(isset($_POST['id'])){
  $student = $actionClass->get_student($_POST['id']);
  extract($student);
}
$classList = $actionClass->list_class();
?>
<div class="container-fluid">
    <form id="student-form" method="POST">
      <input type="hidden" name="id" value="<?= $id ?? "" ?>">
        <div class="row">
            <div class="col-12">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="class_id" class="form-label mb-0">Class Name & Subject</label>
                        <a href="javascript:void(0)" id="toggle-new-class" class="text-primary small text-decoration-none" style="font-size: 0.8rem; font-weight: 500;">+ បង្កើតថ្នាក់ថ្មី (Create Class)</a>
                    </div>
                    <select type="text" class="form-select" id="class_id" name="class_id" required="required">
                      <option value="" <?= !isset($id) ? "selected" : "" ?> disabled> -- Select Class Here -- </option>
                      <?php if(!empty($classList) && is_array($classList)): ?>
                      <?php foreach($classList as $row): ?>
                        <option value="<?= $row['id'] ?>" <?= (isset($class_id) && $class_id == $row['id']) ? "selected" : "" ?>><?= $row['name'] ?></option>
                      <?php endforeach; ?>
                      <?php endif; ?>
                    </select>

                    <!-- Quick Class Creator Widget -->
                    <div id="quick-class-section" class="mt-2 p-2 border rounded bg-light d-none">
                        <label for="new_class_name" class="form-label small fw-bold text-dark mb-1" style="font-size: 0.75rem;">ឈ្មោះថ្នាក់រៀន និងមុខវិជ្ជាថ្មី (New Class & Subject)</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="new_class_name" class="form-control" placeholder="ឧ. MMO - HTML5">
                            <button type="button" id="save-quick-class" class="btn btn-primary btn-sm px-3">រក្សាទុក (Save)</button>
                        </div>
                        <div id="quick-class-msg" class="small mt-1 d-none"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">គោត្តនាម-នាម (Khmer Name) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= $name ?? "" ?>" required="required" placeholder="ឧទាហរណ៍៖ អេឡុន ពេញ">
                </div>
                <div class="mb-3">
                    <label for="name_latin" class="form-label">គោត្តនាម-នាម ជាអក្សរឡាតាំង (Latin Name) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name_latin" name="name_latin" value="<?= $name_latin ?? "" ?>" required="required" placeholder="ဥទាហរណ៍៖ ELON PENH">
                </div>
                <div class="mb-3">
                    <label for="gender" class="form-label">ភេទ (Gender) <span class="text-danger">*</span></label>
                    <select class="form-select" id="gender" name="gender" required="required">
                        <option value="" <?= !isset($gender) ? "selected" : "" ?> disabled> -- ជ្រើសរើសភេទ -- </option>
                        <option value="Male" <?= (isset($gender) && $gender == 'Male') ? "selected" : "" ?>>ប្រុស (Male)</option>
                        <option value="Female" <?= (isset($gender) && $gender == 'Female') ? "selected" : "" ?>>ស្រី (Female)</option>
                    </select>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
  $(function() {
    // Toggle Quick Class Creator
    $('#toggle-new-class').click(function(e) {
      e.preventDefault();
      var section = $('#quick-class-section');
      section.toggleClass('d-none');
      if(!section.hasClass('d-none')) {
        $('#new_class_name').focus();
      } else {
        $('#new_class_name').val('');
        $('#quick-class-msg').addClass('d-none').text('').removeClass('text-success text-danger text-muted');
      }
    });

    // Handle Enter key on quick class input
    $('#new_class_name').keypress(function(e) {
      if(e.which == 13) {
        e.preventDefault();
        $('#save-quick-class').click();
      }
    });

    // Save Quick Class
    $('#save-quick-class').click(function(e) {
      e.preventDefault();
      var name = $('#new_class_name').val().trim();
      var msgDiv = $('#quick-class-msg');
      
      if(name === '') {
        msgDiv.removeClass('d-none text-success text-muted').addClass('text-danger').text('សូមបញ្ចូលឈ្មោះថ្នាក់រៀន! (Please enter a class name!)');
        return;
      }
      
      // Disable inputs
      $('#new_class_name, #save-quick-class').prop('disabled', true);
      msgDiv.removeClass('d-none text-danger text-success').addClass('text-muted').text('កំពុងរក្សាទុក... (Saving...)');
      
      $.ajax({
        url: "ajax-api.php?action=save_class",
        method: "POST",
        data: { name: name, quick_save: 1 },
        dataType: 'JSON',
        error: (err) => {
          console.warn(err);
          msgDiv.removeClass('text-muted').addClass('text-danger').text('មានកំហុសក្នុងការតភ្ជាប់! (Connection error!)');
          $('#new_class_name, #save-quick-class').prop('disabled', false);
        },
        success: function(resp) {
          if(resp?.status == 'success') {
            var newId = resp.id;
            // Add to select dropdown and select it
            var newOption = new Option(name, newId, true, true);
            $('#class_id').append(newOption).trigger('change');
            
            // Clear and hide section
            $('#new_class_name').val('');
            $('#quick-class-section').addClass('d-none');
            msgDiv.addClass('d-none').text('');
          } else {
            var errorMsg = resp?.msg || 'មានកំហុសក្នុងការរក្សាទុក! (Save error!)';
            // Translate common duplicate error
            if(errorMsg === 'Class Name Already Exists!') {
              errorMsg = 'ឈ្មោះថ្នាក់នេះមានរួចហើយ! (Class name already exists!)';
            }
            msgDiv.removeClass('text-muted').addClass('text-danger').text(errorMsg);
          }
          $('#new_class_name, #save-quick-class').prop('disabled', false);
        }
      });
    });

    $('#student-form').submit(function(e){
      e.preventDefault()
      var _this = $(this)
      start_loader();
      $(uniModal).find('.flashdata').remove()
      var flashData = $('<div>')
      flashData.addClass('flashdata mb-3')
      flashData.html(`<div class="d-flex w-100 align-items-center flex-wrap">
                        <div class="col-11 flashdata-msg"></div>
                        <div class="col-1 text-center">
                            <a href="javascript:void(0)" onclick="this.closest('.flashdata').remove()" class="flashdata-close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="align-middle"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></a>
                        </div>
                </div>`);
      $.ajax({
        url: "ajax-api.php?action=save_student",
        method: "POST",
        data: $(this).serialize(),
        dataType:'JSON',
        error: (err)=>{
          flashData.find('.flashdata-msg').text(`An error occured!`)
          flashData.addClass('flashdata-danger')
          _this.prepend(flashData)
          end_loader();
          console.warn(err)
        },
        success: function(resp){
          if(resp?.status == 'success'){
            location.reload()
          }else{
            if(resp?.msg != ''){
              flashData.find('.flashdata-msg').text(`${resp?.msg}`)
              flashData.addClass('flashdata-danger')
              _this.prepend(flashData)
              end_loader();
            }
          }
        }
      })
    })
  });
</script>