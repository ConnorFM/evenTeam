<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/css/calendar.css">

    <title>evenTeam</title>
  </head>
  <body>
    <nav class="navbar navbar-dark bg-primary mb-3">
      <a href="/index.php" class="navbar-brand">evenTeam</a>

    </nav>

    <?php
    require '../src/Date/Month.php';

    try{

    $month = new App\Date\Month($_GET['month'] ?? null, $_GET['year'] ?? null);
    } catch (\Exception $e){
      $month = new App\Date\Month();
    }

    $start = $month->getStartingDay()->modify('last monday');

    ?>
    <div class="d-flex flex-row align-items-center justify-content-between mx-sm-3">
      <h1><?= $month->toString(); ?></h1>
      <div>
        <a href="/index.php?month=<?= $month->previousMonth()->month; ?>&year=<?= $month->previousMonth()->year; ?>" class="btn btn-primary">&lt;</a>
        <a href="/index.php?month=<?= $month->nextMonth()->month; ?>&year=<?= $month->nextMonth()->year; ?>" class="btn btn-primary">&gt;</a>
      </div>
    </div>




    <table class="calendar__table calendar__table--<?= $month->getWeeks(); ?>weeks">
      <?php for ($i=0; $i < $month->getWeeks(); $i++): ?>
      <tr>

        <?php foreach ($month->days as $k => $day):
          $date =(clone $start)->modify("+" . ($k +$i * 7 ) . "days")
          ?>
        <td class="<?= $month->withinMonth($date) ? '' :'calendar__othermonth'; ?>">
          <?php if ($i ===0): ?>
          <div class="calendar__weekday"><?= $day; ?></div>
          <?php endif; ?>
          <div class="calendar__day"><?= $date->format('d'); ?></div>
          </td>
        <?php endforeach; ?>
      <?php endfor; ?>

    </table>





    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
