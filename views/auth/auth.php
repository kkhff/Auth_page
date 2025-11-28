<?php
require_once ROOT_PATH . '/views/layout/header.php'; 

$action = $action ?? 'login';?>


<div class="container mt-3">
    <!-- Login -->
    <?php if($action == 'login'):?>
        <div class="card col-md-6 offset-md-3 shadow-lg rounded-3">
          <div class="card-header bg-primary text-white">
            <h2 class="card-title text-center" >Log in</h2>
          </div>
          <div class="card-body">
            <!-- Message -->
             <?php if(isset($_SESSION['flash'])):?>
               <div class="alert alert-<?php echo htmlspecialchars($_SESSION['flash']['type']) ?>" role="alert">
                <?php echo htmlspecialchars($_SESSION['flash']['message']) ?>
              </div>
            <?php 
            unset($_SESSION['flash']);
            endif; 
            ?>
            <form action="/authorize" method="POST">
              <label for="identifier">Username / Email</label>
                <input type="text" class="form-control mb-3" id="identifier" name="identifier" autocomplete="off" style="background-color:#dadada;" required>
                <label for="password">Password</label>
                <input type="password" class="form-control mb-3" id="password" name="password" autocomplete="off" style="background-color:#dadada;"  required>
                <button class="btn btn-outline-secondary" type="button" id="passwordToggle">👁️</button> <br>
                <button class="btn btn-primary mt-3" type="submit">Log in</button> <br> <br>
                <p>don't have an account yet?<a href="/signup"> Sign up</a></p>
            </form>
          </div>
        </div>
    <!-- Sign up -->
    <?php elseif($action == 'signup'):?>
        <div class="card col-md-6 offset-md-3 rounded-3 shadow-lg">
          <div class="card-header bg-primary text-white">
            <h2 class="card-title text-center" >Sign up</h2>
          </div>
          <div class="card-body">
            <!-- Message -->
            <?php
              $old_input = isset($_SESSION['old_input']) ? $_SESSION['old_input'] : [];
              unset($_SESSION['old_input']);
            ?>
             <?php if(isset($_SESSION['flash'])):?>
               <div class="alert alert-<?php echo htmlspecialchars($_SESSION['flash']['type']) ?>" role="alert">
                <?php echo htmlspecialchars($_SESSION['flash']['message']) ?>
              </div>
            <?php 
            unset($_SESSION['flash']);
            endif; 
            ?>
            <form action="/regist" method="POST">
              <label for="username">Username</label>
              <input type="text" class="form-control  mb-3" 
              value="<?php echo htmlspecialchars($old_input['username'] ?? ''); ?>" id="username" 
              name="username" autocomplete="off" style="background-color:#dadada;" required>
              <label for="email">Email</label>
              <input type="email" class="form-control mb-3" 
              value="<?php echo htmlspecialchars($old_input['email'] ?? ''); ?>"id="email" name="email" 
              autocomplete="off" style="background-color:#dadada;" required>
              <label for="password">Password</label>
              <input type="password" name="password" id="password" class="form-control mb-3" minlength="8" style="background-color:#dadada;" required>
              <label for="confirm_password">Confirm Password</label>
              <input type="password" name="confirm_password" id="confirm_password" class="form-control mb-3" style="background-color:#dadada;" required> 
              <button class="btn btn-outline-secondary" type="button" id="passwordToggle">👁️</button> <br>
              <button class="btn btn-primary mt-3" type="submit">Sign up</button> <br> <br>
              <p>already have an account? <a href="/login">Log in</a></p>
            </form>
          </div>
        </div>
    <?php else:?>
        <div class="alert alert-danger" role="alert">
          Halaman tidak ditemukan.
        </div>
    <?php endif;?>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const passwordInputConfirm = document.getElementById('confirm_password');
    const passwordToggle = document.getElementById('passwordToggle');

    if(passwordToggle){
        passwordToggle.addEventListener('click', () => {
            if (passwordInput.type === 'password' && (passwordInputConfirm === null || passwordInputConfirm.type === 'password')) {
                passwordInput.type = 'text';
                if(passwordInputConfirm){
                    passwordInputConfirm.type = 'text';
                }
                passwordToggle.textContent = '🔒';
            } else {
                if(passwordInputConfirm){
                    passwordInputConfirm.type = 'password';
                }
                passwordInput.type = 'password';
                passwordToggle.textContent = '👁️';
            }
        });
    }
</script>

<?php require_once ROOT_PATH . '/views/layout/footer.php'; ?>