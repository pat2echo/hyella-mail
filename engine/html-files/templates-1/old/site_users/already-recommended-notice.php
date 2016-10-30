<div class="alert alert-success" >
	<h4><i class="icon-check"></i>APPLICANT HAS BEEN RECOMMENDED BY</h4>
	<hr />
	<p>RECOMMENDER FULL NAME: <strong><?php if( isset( $data["name"] ) )echo $data["name"]; ?></strong></p>
	<p>RECOMMENDER EMAIL: <strong><?php if( isset( $data["email"] ) )echo $data["email"]; ?></strong></p>
	<p>RECOMMENDER STATUS: <strong><?php if( isset( $data["status"] ) )echo $data["status"]; ?></strong></p>
</div>