{% extends 'templates/default.php' %}

{% block content %}
<div class="row">
  <div class="col-sm-12 text-center text-capitalize">
      <h1>Expenses on {{name}} in {{appData.nav.display}}</h1>
  </div>
</div>
<div class="row">
  <div class="col-sm-12">
  <div class="col-sm-4">
    <div class="panel panel-danger">
      <div class="panel-heading">
       <h3 class="panel-title text-center">Total Spending </h3>
     </div>
      <div class="panel-body"  {% if not exp %} style="min-height: 400px;display: flex;justify-content: center; align-items: center;" {% endif %}>
        <div id="morris-pie-chart-exp">
          {% if not exp %} No Data Available {% endif %}
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-8">
    <div class="panel panel-default">
        <div class="panel-heading">
          <div class="row">
              <div class="col-xs-12">
                  <span class="tx-2x">Monthly Spending</span>
              </div>
          </div>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div id="morris-bar-chart"></div>
        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
  </div>
  <div class="col-sm-12">
    <div class="panel panel-danger">
      <div class="panel-heading">
       <h3 class="panel-title text-center">Expenses on {{name}}</h3>
     </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-xs-12">
            <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Expense</th>
                  <th>Cost</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody>
                {%for prod in products %}
                <tr>
                  <td>{{prod.date|date('d F Y')}}</td>
                  <td>{{prod.name}}</td>
                  <td>{{prod.cost}}</td>
                  <td id="{{prod.exp_id}}"><a style="color:#fff;" href="#" class="btn btn-info btn-raised" data-show-modal="#updateExp" data-exp-name="{{prod.name}}" data-exp-cost="{{prod.cost}}" data-exp-date="{{prod.date|date('Y/m/d')}}" data-exp-id="{{prod.exp_id}}" >Edit</a></td>
                  <td><a style="color:#fff;" href="#" class="btn btn-danger btn-raised" data-exp-delete="#deleteModal" data-exp-id="{{prod.exp_id}}" >Delete</a></td>
                </tr>
                {%endfor%}
              </tbody>
            </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<div class="modal fade" id="updateExp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title text-capitalize">Edit Expense </h4>
      </div>
      <div class="modal-body">
          <form name="addForm" id="addForm" method="post" action="/expense/update">
            <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="Enter Item Name">
            </div>
            <div class="form-group">
                <input type="text" class="form-control money" name="cost"placeholder="Enter Amount">
            </div>
            <div class="form-group">
                <input type="text" class="form-control datepicker" name="date" placeholder="Date" data-provide="datepicker">
            </div>
            <div class="form-group">
              <label for="tags">Tags</label>
              <select class="form-control" name="tags[]" id="tags" multiple="multiple" style="width:100%;height:50px;">
                {% for tag in tags %}
                <option value="{{tag.id}}">{{ tag.name }}</option>
                {% endfor %}
              </select>
            </div>
              <input type="hidden" name="{{csrf_key}}" value="{{csrf_token}}"/>
              <input type="hidden" name="user_id" value="{{auth.user_id}}"/>
              <input type="hidden" name="exp_id" />
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" id="save" class="btn btn-primary" >Save</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="row" style="margin-top:35px">
        <div class="col-xs-12 text-center">
          <span class="glyphicon glyphicon-warning-sign text-danger fa-4x"></span>
        </div>
      </div>

        <h3 class="text-capitalize text-center">Are You Sure You want to Delete!!!</h3>
        <div class="modal-footer">
          <form name="deleteForm" action="/expense/delete" method="post">
            <input type="hidden" name="exp_id">
            <input type="hidden" name="name" value="{{products.0.name}}">
            <input type="hidden" name="{{csrf_key}}" value="{{csrf_token}}"/>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger" >Delete</button>
          </form>
        </div>
    </div>
  </div>
</div>
{% include 'parts/confirmbox.php'%}
{% endblock %}

{% block js %}
  <script type="text/javascript" src="/js/knob.js"></script>
  <script type="text/javascript">
  {% if exp %}
  Morris.Donut({
  element: 'morris-pie-chart-exp',
  data: [
    {label: "{{name|raw}}", value:{{exp}} },
  ],
  formatter:function (y, data) { return '$'+(y).formatMoney(2,'.',','); } ,
  colors:["#f44336"],
  resize:true
  });
{% endif %}
    Morris.Bar({
    element: 'morris-bar-chart',
    data: [
      {%for key,val in monthly_exp%}
          { d: '{{(appData.nav.display ~'-'~key~'-1')|date('M')}}', a: {{val}} },
      {%endfor%}
    ],
    xkey: 'd',
    ykeys: ['a'],
    labels: ['Expenses'],
    barColors: ['#e74c3c'],
    preUnits:'$',
    resize:true
    });

    $.fn.select2.defaults.set("theme", "classic");
     var selectTags = $("#tags").select2({
        tags: "true",
        placeholder: "Tags",
        allowClear: true,
     });



    $("a[data-show-modal]").on("click",function(event){
      event.preventDefault();
      document.addForm.name.value = $(this).attr('data-exp-name');
      document.addForm.cost.value = $(this).attr('data-exp-cost');
      document.addForm.date.value = $(this).attr('data-exp-date');
      document.addForm.exp_id.value = $(this).attr('data-exp-id');
      $("#tags").val('');
      selectTags.trigger("change");
      $.ajax({
         method: "GET",
         url: "/api/expenses/"+$(this).attr('data-exp-id')
     }).done(function( data ) {
         var obj = data;

         $.each( obj.exp_tags, function( key, value ) {
            $("#tags option[value='"+value.tag_id+"']").prop('selected', true);
            selectTags.trigger("change");
         });
     });

      $($(this).attr('data-show-modal')).modal('show');

    });
    $("a[data-exp-delete]").on("click",function(event){
      event.preventDefault();
      var exp = $(this).attr('data-exp-id');
      document.deleteForm.exp_id.value = exp;
      $($(this).attr('data-exp-delete')).modal('show');
    });

</script>
{% endblock %}
