{% include 'assets/header.tpl'%}
<div class="container-fluid">
    <h1>Обьекты</h1>
    <h2>Поиск</h2>
    <form action="/">
        <div class="form-group">
            <label>Ид.пользователя</label>
            <input type="number" name="search" value="{{search?search:''}}" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary mb-4">Поиск</button>
    </form>
    <a class="btn btn-primary mb-3 mt-3" href="/index/show" role="button">Добавить объект</a>
    {% if objects %}
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Ид.пользователя</th>
            <th scope="col">Название</th>
            <th scope="col">Главная точка(широта)</th>
            <th scope="col">Главная точка(долгота)</th>
            <th scope="col">Адрес</th>
            <th scope="col">Контур</th>
            <th scope="col">Иные параметры</th>
            <th scope="col">Действия</th>
        </tr>
        </thead>
        <tbody>
        {% for objects in objects %}
        <tr>
            <th>{{objects.id}}</th>
            <td>{{objects.user_id}}</td>
            <td>{{objects.name}}</td>
            <td>{{objects.main_lat}}</td>
            <td>{{objects.main_lon}}</td>
            <td>{{objects.address}}</td>
            <td>{{objects.circuit}}</td>
            <td>{{objects.extends}}</td>
            <td>
                <a class="btn btn-success mb-1 mt-1" href="/index/edit/id/{{objects.id}}" role="button">Редактировать
                    объект</a>
                <a class="btn btn-danger mb-1 mt-1" href="/index/delete/id/{{objects.id}}" role="button">Удалить
                    объект</a>
            </td>
        </tr>
        {%endfor%}
        </tbody>
    </table>
    {%else%}
    <div class="alert alert-danger" role="alert">
        На данный момент объектов нет
    </div>
    {%endif%}
</div>
{% include 'assets/footer.tpl'%}