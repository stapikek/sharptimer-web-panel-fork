// Extracted from index.php
(function(){
  if (typeof bindIndexHandlers === 'function') {
    try {
      bindIndexHandlers();
    } catch (e) {
      // Silently ignore to avoid breaking page
      console && console.debug && console.debug('bindIndexHandlers error', e);
    }
  }
})();
