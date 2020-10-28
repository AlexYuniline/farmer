{% include 'assets/header.tpl'%}
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <a class="btn btn-dark mb-3 mt-3" href="/" role="button">Вернуться назад</a>
            <h1>Добавление объекта</h1>
            <form action="/index/create" method="POST">
                <div class="form-group">
                    <label>Ид.пользователя</label>
                    <input type="number" name="user_id" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Название</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Главная точка(широта)</label>
                    <input type="number" name="main_lat" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Главная точка(долгота)</label>
                    <input type="number" name="main_lon" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Адрес</label>
                    <input type="text" name="address" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Контур</label>
                    <input type="text" name="circuit" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Иные параметры</label>
                    <input type="text" name="extends" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary mb-4">Добавить</button>
            </form>
        </div>
    </div>
</div>
{% include 'assets/footer.tpl'%}