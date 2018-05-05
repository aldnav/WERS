<template id="modal-template">
  <transition name="modal">
    <div class="modal-mask pull-right">
      <div class="modal-wrapper ">
        <div class="modal-container ">
          <div class="panel panel-primary">
            <slot name="header">
              default header
            </slot>
          <div class="panel-body">
            <slot name='body'>
              default body
            </slot>
          </div> 
          </div>
          </div>
        </div>
      </div>
  </transition>
</template>
