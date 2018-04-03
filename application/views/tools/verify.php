<div class="row">
	<div class="col-md-12">
     	<h2>Verify Smart Contract</h2>
	 	<hr />
	 	
	 	<div class="note">
        	Step 1 : Enter your Contract Source Code below.<br>
			Step 2 : If the Bytecode generated matches the existing <b>Creation Address</b> Bytecode, the contract is then Verified.<br>
            Step 3 : Contract Source Code is published online and publicably verifiable by anyone.<br>
            <br>
        </div>
        
        <hr />
                        
        <form class="" id="verifyContractForm" data-toggle="validator" role="form">
            <fieldset>
	            <div class="row">
	                <div class="col-md-4 form-group">
		            	<label for="contractAddress">Contract Address:</label>
	                    <div class="input-group">
		                    <div class="input-group-addon"><i class="fa fa-tasks"></i></div>
							<input name="contractAddress" type="text" class="form-control" value="<?php echo $this->uri->segment(3); ?>" id="contractAddress" placeholder="Contract Address" autofocus required>
	                    </div>
	               </div>
	                          

                   	<div class="col-md-3 form-group">
	                    <label for="contractName">Name:</label> 
	                    <div class="input-group">
		                    <div class="input-group-addon"><i class="fa fa-edit"></i></div>
		                    <input name="contractName" type="text" maxlength="100" class="form-control" id="contractName" placeholder="Contract Name" required>
	                    </div>
	                </div>

                    <div class="col-md-3 form-group select-group">
                        <label for="Compiler">Compiler:</label> 
                        <select name="compilerVersions" id="compilerVersions" class="form-control" required>
                            <option value="">
                                [Please select]
                            </option>
							<?php
								if(!empty($compilers)) {
									for($i = count($compilers->builds) - 1; $i >= 0; $i--) {
										echo '<option value="v'.$compilers->builds[$i]->longVersion.'">';
											echo 'v'.$compilers->builds[$i]->longVersion;
										echo '</option>';
									}
									
								}
                           ?>
                    	</select>
                    </div>

                    <div class="col-md-2 form-group select-group">
                        <label for="Optimization">Optimization:</label> 
                        <select name="optimization" id="optimization" class="form-control" required>
                            <option value="1">
                                Yes
                            </option>

                            <option value="0">
                                No
                            </option>
                    	</select>
                    </div>
	            </div>
	            <div class="row">
		            <div class="col-md-12 form-group select-group">
			            <label for="sourceCode">Enter the Solidity Contract Code below:</label>
			            <textarea class="form-control" name="sourceCode" id="sourceCode" placeholder="Source Code" rows="12"></textarea>
		            </div>
	            </div>
	            <div class="row">
		            <div class="col-md-2">
			            <button type="submit" id="verifyContract" class="btn btn-success">Verify Contract</button>
		            </div>
		            <div class="col-md-10 alert-message">
			            <div style="display:none;" role="alert" id="success_message"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
		            </div>
	            </div>
            </fieldset>
        </form>
	</div>
</div>
                                
                            
                               

                                