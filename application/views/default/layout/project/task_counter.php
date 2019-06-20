<ul id="typefilter1" class="nav nav-tabs">
	<li onclick="typefilter(this.id);" id="all" class="active">
		<a  href="#subtab_1" data-toggle="tab">
			<label> All Tasks</label>
			<div class="tasklbl blue"> <?php echo $tot_task;?> / <?php echo $my_task;?> </div>
		</a>
	</li>
	<li onclick="typefilter(this.id);" id="ut"><a  href="#subtab_2" data-toggle="tab">
		<label> Upcoming Tasks</label>
		<div class="tasklbl yellow"> <?php echo $tot_upcoming_task;?> / <?php echo $my_upcoming_task;?> </div>
	</a></li>
	<li onclick="typefilter(this.id);" id="tt"><a  href="#subtab_3" data-toggle="tab">
		<label> Today's Tasks</label>
		<div class="tasklbl green"> <?php echo $tot_today_task;?>/ <?php echo $my_today_task;?> </div>
	</a></li>
	<li onclick="typefilter(this.id);" id="ot"><a  href="#subtab_4" data-toggle="tab">
		<label> Overdue Tasks</label>
		<div class="tasklbl red"> <?php echo $tot_overdue_task;?> / <?php echo $my_overdue_task;?></div>
	</a></li>
</ul>