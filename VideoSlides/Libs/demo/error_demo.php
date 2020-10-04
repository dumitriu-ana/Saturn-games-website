<?php
  require '../requirements.php';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Error</title>
    <?php
      show_dep();
    ?>
  </head>
  <body>

    <div class="error-container">
      <div class="error-nav">
        Undefined property: mysqli_result
      </div>

      <div class="col-md-8 col-md-offset-2 col-xs-12 code-preview">
        <table class="table">
          <tbody>
            <tr>
              <th scope="row">1</th>
              <td><?php echo htmlspecialchars('<?php'); ?></td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td><?php echo htmlspecialchars("echo msql->select('user')->sort('id')->where('username', 'edimemune')->orWhere('username', 'anadumitriu68')->get()->num_rows;"); ?></td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td><?php echo htmlspecialchars("echo msql->select('user')->sort('id')->where('username', 'edimemune')->orWhere('username', 'anadumitriu68')->get()->num_rows;"); ?></td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td><?php echo htmlspecialchars("echo msql->select('user')->sort('id')->where('username', 'edimemune')->orWhere('username', 'anadumitriu68')->get()->num_rows;"); ?></td>
            </tr>
            <tr>
                <th scope="row">
                  <span class="error">2</span>
                </th>
                <td>
                  <a href="#" data-toggle="tooltip" data-placement="top" title="Line with error">
                    <span class="error">
                    <?php echo htmlspecialchars("echo msql->select('user')->sort('id')->where('username', 'edimemune')->orWhere('username', 'anadumitriu68')->get()->num_rows;"); ?>
                    </span>
                  </a>
                </td>

            </tr>
            <tr>
              <th scope="row">2</th>
              <td><?php echo htmlspecialchars("echo msql->select('user')->sort('id')->where('username', 'edimemune')->orWhere('username', 'anadumitriu68')->get()->num_rows;"); ?></td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td><?php echo htmlspecialchars("echo msql->select('user')->sort('id')->where('username', 'edimemune')->orWhere('username', 'anadumitriu68')->get()->num_rows;"); ?></td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td><?php echo htmlspecialchars("echo msql->select('user')->sort('id')->where('username', 'edimemune')->orWhere('username', 'anadumitriu68')->get()->num_rows;"); ?></td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td><?php echo htmlspecialchars("echo msql->select('user')->sort('id')->where('username', 'edimemune')->orWhere('username', 'anadumitriu68')->get()->num_rows;"); ?></td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td><?php echo htmlspecialchars("echo msql->select('user')->sort('id')->where('username', 'edimemune')->orWhere('username', 'anadumitriu68')->get()->num_rows;"); ?></td>
            </tr>
            <tr>
              <th scope="row">3</th>
              <td><?php echo htmlspecialchars('?>'); ?></td>
            </tr>
          </tbody>
        </table>

      </div>
    </div>

  </body>
</html>
