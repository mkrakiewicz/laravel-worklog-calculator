<template>
<div class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    <slot name="header">Alert</slot>
                </h4>
            </div>
            <div class="modal-body">
                <slot name="content">
                </slot>
            </div>
            <div class="modal-footer">
                <slot name="footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </slot>
            </div>
        </div>
    </div>
</div>
</template>
<script type="text/ecmascript-6">
    export default {
        props: ['modalID']
    }
</script>
