<?php
if (!defined('ABSPATH')) {
    exit;
}

class EBook_Donation_Membership {

    // Példa: egyedi login és regisztrációs lap létrehozása (minimalista)
    public static function render_login_form() {
        // Biztonsági nonce
        $nonce_field = wp_create_nonce('ebook_donation_login_nonce');

        ob_start(); 
        ?>
        <form method="POST" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
            <input type="hidden" name="ebook_donation_login_nonce" value="<?php echo $nonce_field; ?>" />
            
            <label for="username">Felhasználónév</label>
            <input type="text" name="username" required>

            <label for="password">Jelszó</label>
            <input type="password" name="password" required>

            <input type="submit" name="ebook_donation_login_submit" value="Bejelentkezés">
        </form>
        <?php
        return ob_get_clean();
    }

    public static function process_login_form() {
        if (isset($_POST['ebook_donation_login_submit'])) {
            // Ellenőrizzük a nonce-ot
            if ( ! isset($_POST['ebook_donation_login_nonce']) ||
                 ! wp_verify_nonce(sanitize_text_field($_POST['ebook_donation_login_nonce']), 'ebook_donation_login_nonce') ) {
                return;  // Érvénytelen kérés
            }
            $username = sanitize_user($_POST['username']);
            $password = sanitize_text_field($_POST['password']);

            $creds = [
                'user_login'    => $username,
                'user_password' => $password,
                'remember'      => false
            ];
            $user = wp_signon($creds, false);
            if (is_wp_error($user)) {
                echo '<p class="error">Hibás adatok. Próbáld újra!</p>';
            } else {
                // Sikeres bejelentkezés
                wp_redirect(home_url('/members-area'));
                exit;
            }
        }
    }

    // Példa: regisztrációs űrlap
    public static function render_registration_form() {
        $nonce_field = wp_create_nonce('ebook_donation_register_nonce');

        ob_start(); 
        ?>
        <form method="POST">
            <input type="hidden" name="ebook_donation_register_nonce" value="<?php echo $nonce_field; ?>" />

            <label for="username">Felhasználónév</label>
            <input type="text" name="username" required>

            <label for="email">E-mail</label>
            <input type="email" name="email" required>

            <label for="password">Jelszó</label>
            <input type="password" name="password" required>
            
            <label for="role">Tagsági szint</label>
            <select name="role">
                <option value="silver_member">Silver Member</option>
                <option value="gold_member">Gold Member</option>
                <option value="diamond_member">Diamond Member</option>
            </select>

            <input type="submit" name="ebook_donation_register_submit" value="Regisztráció">
        </form>
        <?php
        return ob_get_clean();
    }

    public static function process_registration_form() {
        if (isset($_POST['ebook_donation_register_submit'])) {
            if ( ! isset($_POST['ebook_donation_register_nonce']) ||
                 ! wp_verify_nonce(sanitize_text_field($_POST['ebook_donation_register_nonce']), 'ebook_donation_register_nonce') ) {
                return;  // Érvénytelen kérés
            }

            $username = sanitize_user($_POST['username']);
            $email    = sanitize_email($_POST['email']);
            $password = sanitize_text_field($_POST['password']);
            $role     = sanitize_text_field($_POST['role']);

            $user_id = wp_create_user($username, $password, $email);
            if (!is_wp_error($user_id)) {
                // Új user szerep beállítása
                $user = get_user_by('ID', $user_id);
                $user->set_role($role);

                echo '<p>Sikeres regisztráció!</p>';
            } else {
                echo '<p class="error">Hiba történt: ' . $user_id->get_error_message() . '</p>';
            }
        }
    }
}
