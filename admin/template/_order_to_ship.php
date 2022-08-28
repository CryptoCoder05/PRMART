<section id="order_to_ship">
  <div class="container-fluid">
      <h3>Orders To Ship</h3>
      <hr />
      <table class="table table-condensed table-bordered table-striped">
        <thead>
          <th></th><th>Name</th><th>Phone No.</th><th>Total</th><th>Orderd Date</th><th>Status</th>
        </thead>
        <tbody>
          <?php
          // get undispatched data from transaction table
          foreach (getOrder() as $newOrd) :
            // get address detail from transaction table
            foreach (getAdd($newOrd['address_details']) as $addDet):
            // get address detail from transaction table
            foreach (getPayment($newOrd['payment_details']) as $payDet):
           ?>
          <tr>
            <td><a href="orders.php?txn_id=<?=$newOrd['id']; ?>" class="btn btn-xs btn-info">Details</a></td>
            <td><?=$addDet['first_name'].' '.$addDet['last_name']; ?></td>
            <td><?=$newOrd['phone_no']; ?></td>
            <td><?=money($payDet['subtotal']); ?></td>
            <td><?=pretty_date($newOrd['txn_date']); ?></td>
            <?php if ($newOrd['dispatched'] == 1){
              ?>
              <td>Dispatched</td>
              <?php
            }else {
              ?>
              <td><?=($newOrd['pickup'] == 1)?'Picked Up':'Pending';?></td>
              <?php
            } ?>
          </tr>
        <?php
       endforeach; // closing payment detail loop.
      endforeach; // closing address detail loop.
     endforeach; // closing newOrder loop.
      ?>
        </tbody>
      </table>
  </div>
</section>
