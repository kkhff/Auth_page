<?php
require_once ROOT_PATH . '/views/layout/header.php';
 ?>

<div class="container mt-5">
    <div class="card col-md-6 offset-md-3 shadow-lg rounded-3">
          <div class="card-header bg-primary text-white">
            <h2 class="card-title text-center" >Welcome!</h2>
          </div>
          <div class="card-body">
            <!-- Message -->
            <?php if(isset($_SESSION['username'])): ?>
              <div class="alert alert-info" role="alert">
                <?php echo $_SESSION['username']; ?>
              </div>
            <?php endif; ?>
             <?php if(isset($_SESSION['flashmsg'])):?>
               <div class="alert alert-<?php echo htmlspecialchars($_SESSION['flashmsg']['type']) ?>" role="alert">
                <?php echo htmlspecialchars($_SESSION['flashmsg']['message']) ?>
              </div>
            <?php 
            unset($_SESSION['flashmsg']);
            endif; 
            ?>
            <br>
                <a href="/logout" class="btn btn-warning">Log out</a>


                <button type="button " class="btn btn-danger " data-bs-toggle="modal" data-bs-target="#confirmDelete" >Delete Account</button>
          </div>
    </div>
    <form action="/delete" method="POST">
        <div class="modal" tabindex="-1" id="confirmDelete">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Warning !!!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body" >
                <p>Are you sure you want to delete your account?</p>
                <p>This action cannot be undone!</p>
                <label for="deletePassword">Password</label>
                <input type="password" class="form-control mb-3" id="deletePassword" name="deletePassword" style="background-color:#dadada;" required>
                <button class="btn btn-outline-secondary " type="button" id="passwordToggle" >👁️</button>
              </div>
              <div class="modal-footer" >
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" >DELETE</button>
              </div>
            </div>
          </div>
        </div>
    </form>
</div>

<script>
  if(document.getElementById('confirmDelete')) {
    const deletePasswordInput = document.getElementById('deletePassword');
    const passwordToggle = document.getElementById('passwordToggle');

    passwordToggle.addEventListener('click', () => {
        if (deletePasswordInput.type === 'password') {
            deletePasswordInput.type = 'text';
            passwordToggle.textContent = '🔒';
        } else {
            deletePasswordInput.type = 'password';
            passwordToggle.textContent = '👁️';
        }
    });
  }
</script>

<?php require_once ROOT_PATH . '/views/layout/footer.php'; ?>