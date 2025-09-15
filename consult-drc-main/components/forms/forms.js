// forms.js - JS commun pour tous les formulaires
// Fournit une API d'initialisation réutilisable pour les formulaires injectés dynamiquement.

(function(){
  function initTDG(root){
    const scope = root || document;
    const tdgForm = scope.querySelector('#tdg-form');
    if (!tdgForm) return;

    const sectionTrad = tdgForm.querySelector('#section-traduction');
    const sectionAssist = tdgForm.querySelector('#section-assistance');
    const sectionGuide = tdgForm.querySelector('#section-guide');
    const cbTrad = tdgForm.querySelector('#service-traduction');
    const cbAssist = tdgForm.querySelector('#service-assistance');
    const cbGuide = tdgForm.querySelector('#service-guide');

    const update = () => {
      if (sectionTrad) sectionTrad.style.display = cbTrad && cbTrad.checked ? '' : 'none';
      if (sectionAssist) sectionAssist.style.display = cbAssist && cbAssist.checked ? '' : 'none';
      if (sectionGuide) sectionGuide.style.display = cbGuide && cbGuide.checked ? '' : 'none';
    };

    tdgForm.querySelectorAll('input[type=checkbox][name="services[]"]').forEach(cb => {
      cb.removeEventListener('change', update);
      cb.addEventListener('change', update);
    });
    update();
  }

  function initVisa(root){
    const scope = root || document;
    const visaForm = scope.querySelector('#visa-carte-form');
    if (!visaForm) return;

    const updateSectionsVisa = () => {
      const checked = visaForm.querySelector('input[name="but_sejour"]:checked');
      visaForm.querySelectorAll('.visa-section').forEach(sec => sec.style.display = 'none');
      if (checked) {
        const sec = visaForm.querySelector('#section-' + checked.value);
        if (sec) sec.style.display = '';
      }
    };

    // Main sections
    visaForm.querySelectorAll('input[type=radio][name="but_sejour"]').forEach(radio => {
      radio.removeEventListener('change', updateSectionsVisa);
      radio.addEventListener('change', updateSectionsVisa);
    });

    // Établissement subsections
    const etabType = visaForm.querySelector('select[name="etab_type"]');
    const etabOrd = visaForm.querySelector('#etab-ordinaire');
    const etabTrav = visaForm.querySelector('#etab-travail');
    const etabPerm = visaForm.querySelector('#etab-permanent');
    if (etabType && etabOrd && etabTrav && etabPerm) {
      const updateEtab = () => {
        const v = etabType.value || '';
        if (etabOrd) etabOrd.style.display = (v === 'ordinaire') ? '' : 'none';
        if (etabTrav) etabTrav.style.display = (v === 'travail') ? '' : 'none';
        if (etabPerm) etabPerm.style.display = (v === 'permanent') ? '' : 'none';
      };
      etabType.removeEventListener('change', updateEtab);
      etabType.addEventListener('change', updateEtab);
      updateEtab();
    }

    updateSectionsVisa();
  }

  function initAll(root){
    initTDG(root);
    initVisa(root);
  }

  // Expose API for dynamic pages
  window.FormsInit = Object.freeze({ initTDG, initVisa, initAll });

  // Auto-init when page loads
  document.addEventListener('DOMContentLoaded', function(){ initAll(document); });
})();
