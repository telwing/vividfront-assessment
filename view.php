<!doctype html>
<html lang="en-US">
<head>
    <title>VividFront Database Manager</title>
</head>
<body>
    <?php if ($error) : ?>
        <p>ERROR: <?= $error; ?></p>
    <?php endif; ?>
    <?php if ($success) : ?>
        <p>SUCCESS!</p>
    <?php endif; ?>
    <form method="post" action="database.php" id="addEntryForm">
        <div class="form-field">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="" required="required" />
        </div>
        <div class="form-field">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" value="" require="required" />
        </div>
        <div class="form-field">
            <label for="state">State</label>
            <select id="state" name="state">
                <?php foreach (DatabaseEntryRepository::$states as $state) : ?>
                    <option value="<?= $state; ?>"><?= $state; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-field">
            <label for="interested">Interested</label>
            <input type="checkbox" id="interested" name="interested" value="1" />
        </div>
        <input type="hidden" name="action" value="<?= DatabaseEntryRepository::ADD_ACTION; ?>" />
        <button type="submit">Create</button>
    </form>
    <hr />
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>State</th>
                <th>Interested</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($entries)) : ?>
            <tr>
                <td colspan="6">No entries to display</td>
            </tr>
        <?php else : ?>
            <?php foreach ($entries as $entry) : ?>
                <tr>
                    <td><?= $entry['id']; ?></td>
                    <td><?= $entry['name']; ?></td>
                    <td><?= $entry['email']; ?></td>
                    <td><?= $entry['state']; ?></td>
                    <td><?= $entry['interested']; ?></td>
                    <td>
                        <form method="post" action="database.php">
                            <input type="hidden" name="id" value="<?= $entry['id']; ?>" />
                            <input type="hidden" name="action" value="<?= DatabaseEntryRepository::DELETE_ACTION; ?>" />
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
    <script>var stateList = <?= json_encode(DatabaseEntryRepository::$states); ?>;</script>
    <script src="form.js"></script>
</body>
</html>