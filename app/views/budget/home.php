{% extends 'templates/default.php' %}

{% block content %}
<div class="row">
    <div class="col-sm-12">
        <h2>Budgets</h2>
    </div>
    <div class="col-sm-12">
        <button style="margin-bottom: 20px;" type="button" id="addItem" class="btn btn-info btn-raised" data-toggle="modal" data-target="#addBudget">
            <i class="fa fa-plus"></i> Add Budget
        </button>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-6">
      <div class="panel panel-info">
          <div class="panel-heading">
              <h2>Food Budget</h2>
          </div>
          <div class="alert alert-info">
            <div class="row">
              <div class="col-xs-6">
                <h4>Spent</h4>
                <p>$200</p>
              </div>
              <div class="col-xs-6">
                <h4>Budgeted</h4>
                <p>$1000</p>
              </div>
            </div>
          </div>
          <!-- /.panel-heading -->
          <div class="panel-body" >
            <p>
              You can keep spending
            </p>
            <p class="fa-2x">
              $60.00
            </p>
            <p>
              each day!
            </p>
            <div class="progress budget-progress">
              <div class="progress-bar progress-bar-success" style="width: 20%">20%</div>
            </div>
          </div>
          <!-- /.panel-body -->
          <div class="panel-footer">
            <h2>Tags</h2>
            <div class="panel-body">
                <div id="morris-pie-chart-tags">
                  {% if not appData.exp_tags %} No Data Available {% endif %}
                </div>
            </div>
          </div>
      </div>
      <!-- /.panel -->
    </div>
    <div class="col-xs-12 col-sm-6 ">
      <h1>side 2</h1>
    </div>
</div>
<div class="modal fade" id="addBudget" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title text-capitalize">Add Expense </h4>
      </div>
      <div class="modal-body">
          <form name="addForm" id="addForm" method="post" action="{{ baseUrl() }}/expenses/add">
            <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="Enter Item Name">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="cost" id="money" placeholder="Enter Amount">
            </div>
            <div class="form-group">
                <input type="text" class="form-control datepicker" name="date" placeholder="Date" data-provide="datepicker" onfocus="blur();" onkeydown="return false">
            </div>
            <div class="form-group">
              <label for="tags">Tags</label>
              <select class="form-control" name="tags[]" id="tags" multiple="multiple" style="width:100%;height:50px;">
                {% for tag in appData.tags %}
                <option value="{{tag.id}}">{{ tag.name }}</option>
                {% endfor %}
              </select>
            </div>
              <input type="hidden" name="{{csrf_key}}" value="{{csrf_token}}"/>
              <input type="hidden" name="user_id" value="{{auth.user_id}}"/>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" id="save" class="btn btn-primary" >Save</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

{% endblock %}

{% block js %}
  <script type="text/javascript">
  Morris.Donut({
  element: 'morris-pie-chart-tags',
  data: [
    {% for tag,cost in appData.exp_tags %}
        {label: "{{tag|raw}}", value:{{cost}} },
    {% endfor%}
  ],
  formatter:function (y, data) { return '$'+(y).formatMoney(2,'.',','); } ,
  colors:['#FF3D00'],
  resize:true
  });

  $.fn.select2.defaults.set("theme", "classic");
  $("#tags").select2({
    tags: "true",
    placeholder: "Tags",
    allowClear: true,
  });
  </script>
{% endblock %}
