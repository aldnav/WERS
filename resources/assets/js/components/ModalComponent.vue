<template id="modal-template">
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">

          <div class="modal-header">
            <slot name="header">
              Report Incident
            </slot>
          </div>

          <div class="modal-body">
            <slot name='body'>
              default body
            </slot>
          </div>
          <div class="modal-footer">
            <slot name='footer'>
              <button class="btn btn-sm js-add-btn" id="show-modal" @click="$emit('close')">Close</button>
            </slot>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>
