<div class="lxhm-wrapper">
  <form name="lxhm-form" id="lxhm-form">
    
    <div class="lxhm-container">
      <div class="lxhm-section lxhm-row">
        
        <span class="title">Schritt 1:</span>
        <div class="action">
          <select name="lxhm-server-type" required>
            <option value="null" selected disabled>Serverform Auswählen</option>
            <option value="miniserver">Miniserver</option>
            <option value="miniserver-go">Miniserver GO</option>
          </select>
          <span class="lxhm-server-warning">
            <svg viewBox="0 0 24 24"><path d="M13,13H11V7H13M13,17H11V15H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" /></svg>
            Nachträgliches Ändern der Serverform setzt alle Bereich-Optionen zurück.
          </span>
        </div>
        
      </div>
    </div>
    

    <div class="lxhm-container">
      <div class="lxhm-section lxhm-row">
        
        <span class="title">Schritt 2:</span>
        <div class="action">
          <button type="button" id="lxhm-add-room" disabled>Raum hinzufügen</button>
        </div>
        
      </div>
      <ul class="lxhm-section lxhm-column" id="lxhm-room-container"></ul>
    </div>
    
    
    <div class="lxhm-container">
      <div class="lxhm-section lxhm-row">
      
        <span class="title">Schritt 3:</span>
        <div class="action">
          <button type="button" id="lxhm-calculate" disabled>Kalkulieren</button>
        </div>
      
      </div>
      <div class="lxhm-section lxhm-column" id="lxhm-product-container"></div>
    </div>
    
    
    <div class="lxhm-container">
      <div class="lxhm-section lxhm-row">
      
        <span class="title">Schritt 4:</span>
        <div class="action">
          <button type="button" id="lxhm-add-to-cart" disabled>Zum Warenkorb hinzufügen</button>
          <a href="<?= wc_get_cart_url(); ?>" class="lxhm-link-button" id="lxhm-cart-link">
            <svg viewBox="0 0 24 24"><path d="M17,18C15.89,18 15,18.89 15,20A2,2 0 0,0 17,22A2,2 0 0,0 19,20C19,18.89 18.1,18 17,18M1,2V4H3L6.6,11.59L5.24,14.04C5.09,14.32 5,14.65 5,15A2,2 0 0,0 7,17H19V15H7.42A0.25,0.25 0 0,1 7.17,14.75C7.17,14.7 7.18,14.66 7.2,14.63L8.1,13H15.55C16.3,13 16.96,12.58 17.3,11.97L20.88,5.5C20.95,5.34 21,5.17 21,5A1,1 0 0,0 20,4H5.21L4.27,2M7,18C5.89,18 5,18.89 5,20A2,2 0 0,0 7,22A2,2 0 0,0 9,20C9,18.89 8.1,18 7,18Z" /></svg>
            Zum Warenkorb
          </a>
        </div>
      </div>
    </div>

  </form>
</div>