<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" style="overflow-x: auto;">
            <div class="panel-heading">Order List</div>
            <div class="panel-body" style="overflow-x: auto;">
                <div class="row">
                    <div class="col-md-3 col-md-offset-6">
                        <div class="form-group">
                            <?php echo form_label('Filter by Payment Status', '', ['class' => 'control-label']) ?>
                            <?php echo form_dropdown(['name' => 'payment_status_filter', 'id' => 'payment_status_filter', 'class' => 'form-control', 'data-url' => base_url('order/get-orders')], ['' => 'All', 'Not Processed' => 'Not Processed', 'Processing' => 'Processing', 'Processed' => 'Planted']); ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <?php echo form_label('Filter by Due Date', '', ['class' => 'control-label']) ?>
                            <?php echo form_dropdown(['name' => 'filter_dropdown', 'id' => 'filter_dropdown', 'class' => 'form-control', 'data-url' => base_url('order/get-orders')], ['All' => 'All', 'Due' => 'Due Payment']); ?>
                        </div>
                    </div>
                </div>
                <div id="list-wrapper"
                     data-url="<?php echo base_url('order/get-orders/' . (isset($filter) ? $filter : '')); ?>">
                </div>
                <div class="modal fade" id="fcmModal" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="exampleModalLabel">Send Notification</h3>
                            </div>
                            <div class="modal-body">
                                <form action="<?php echo base_url('order/send-fcm-notification') ?>" id="fcm_noti_form"
                                      method="post" accept-charset="utf-8">
                                    <div class="form-group">
                                        <label for="recipient-name" class="form-control-label">Title:</label>
                                        <input type="text" class="form-control" name="title" id="title">
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" name="fcm_id" id="fcm_id">
                                    </div>
                                    <div class="form-group">
                                        <label for="message-text" class="form-control-label">Message:</label>
                                        <textarea class="form-control" name="message" id="message" rows="10"></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" id="sendFCMMessage" class="btn btn-primary">Send message</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
