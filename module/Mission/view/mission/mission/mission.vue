<template id="mission">
  <div :id="mission.id" class="card bg-default">
    <div class="card-header form-inline">
      <?= $this->formControlGroup($missionForm->get('typeMission')->setAttribute('style', 'width: 50%')) ?>
      &nbsp;, du&nbsp;<?= $this->formControlGroup($missionForm->get('dateDebut')) ?>
      &nbsp;au&nbsp;<?= $this->formControlGroup($missionForm->get('dateFin')) ?>

    </div>
    <div class="card-body">
      <?= $this->form()->openTag($missionForm); ?>
      <div class="row">
        <div class="col-md-6"><?= $this->formControlGroup($missionForm->get('structure')) ?></div>
        <div class="col-md-3"><?= $this->formControlGroup($missionForm->get('missionTauxRemu')) ?></div>
        <div class="col-md-3"><?= $this->formControlGroup($missionForm->get('heures')) ?></div>
      </div>
      <div class="row">
        <div class="col-md-12"><?= $this->formControlGroup($missionForm->get('description')) ?></div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <?= $this->formSubmit($missionForm->get('submit')) ?>
          <a class="btn btn-danger" @click="deleteMission">Suppression de la mission</a>
        </div>

      </div>
      <?= $this->form()->closeTag(); ?>
    </div>
  </div>
</template>

<script>

Vue.component('mission', {
  template: '#mission',
  props: ['mission'],
  methods: {
    submitForm(event)
    {
      var that = this
      $.ajax({
        type: 'POST',
        submitter: event.submitter,
        msg: 'Enregistrement en cours',
        successMsg: 'Enregistrement effectué',
        url: event.target.getAttribute("action"),
        data: this.mission,
        success: function (response) {
          that.mission = response.data;
        }
      });
    },
    deleteMission(mission)
    {
      this.$emit('delete', this.mission);
    }
  }
})

</script>

<style scoped>

</style>