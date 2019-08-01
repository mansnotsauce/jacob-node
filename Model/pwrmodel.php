<?php
class pwr_model {

	public function __construct() {	
		// error_reporting(0);
		$this->dbserver = DB_SERVERNAME;
		$this->dbuser = DB_USERNAME;
		$this->dbpass = DB_PASSWORD;
		$this->dbname = DB_NAME;		
		$this->conn = new mysqli($this->dbserver, $this->dbuser, $this->dbpass, $this->dbname);
		if ($this->conn->connect_error) {
		    die("Connection failed: " . $this->conn->connect_error);
		}
	}

	public function get_account($id) {
		$sql = "SELECT * FROM account WHERE user_id='$id'";
		$result = $this->conn->query($sql);		
		return $result->fetch_assoc();
	}

	public function get_account_byname($name) {
		$sql = "SELECT * FROM account WHERE Name='$name'";
		$result = $this->conn->query($sql);		
		return $result->fetch_assoc();
	}

	public function get_accounts_byteam($team) {
		$sql = "SELECT * FROM account WHERE Team__c='$team' AND Position__c != 'Commercial Energy Consultant' AND  Status__c='Active' AND is_hidden=0";
		$result = $this->conn->query($sql);		
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {
		        $data[] = $row;
		    }
		}
		return $data;
	}

	public function is_team_manager($id) {
		$sql = "SELECT team_manager FROM account WHERE user_id='$id'";
		$result = $this->conn->query($sql);		
		return $result->fetch_assoc();
	}

	public function approve_onboarding_account($id,$param) {
		$sql = "UPDATE account SET Company_Email__c='".$param['Company_Email__c']."',Status__c='".$param['Status__c']."',Onboarding_Complete__c='".$param['Onboarding_Complete__c']."' WHERE user_id='".$id."'";
		if ($this->conn->query($sql) === TRUE) {
			$return['success']=true;
		    return $return;
		} else {
			$return['success'] = false;
			$return['message'] = $this->conn->error;
		    return $return;
		}
	}

	public function update_account($id,$param) {
		if(isset($param['Position__c']) && isset($param['Team__c'])) {
			$sql = "UPDATE account SET Name='".$param['Name']."',Position__c='".$param['Position__c']."',Team__c='".$param['Team__c']."',Phone='".$param['Phone']."' WHERE user_id='".$id."'";
		} else {
			$sql = "UPDATE account SET Name='".$param['Name']."',Phone='".$param['Phone']."' WHERE user_id='".$id."'";
		}		
		if ($this->conn->query($sql) === TRUE) {
			$return['success']=true;
		    return $return;
		} else {
			$return['success'] = false;
			$return['message'] = $this->conn->error;
		    return $return;
		}
	}

	public function add_video($param) {
		$sql = "INSERT INTO videos (category, name, description, link, type, tags, added_by_name, added_by_id) VALUES ('".$param['category']."', '".$param['name']."', '".$param['description']."','".$param['link']."','".$param['type']."','".$param['tags']."','".$_SESSION['user']['name']."','".$_SESSION['user_id']."')";

		if ($this->conn->query($sql) === TRUE) {
			$return['success']=true;
		    return $return;
		} else {
			$return['success'] = false;
			$return['message'] = $this->conn->error;
		    return $return;
		}
		$this->conn->close();
	}

	public function add_goal($param) {	
		if($param['month']==NULL) {
			$result = $this->get_goals_userid_period($param['user_id'],$param['week_number'],NULL);
		} else {
			$result = $this->get_goals_userid_period($param['user_id'],NULL,$param['month']);
		}

		if($result != NULL) {
			$sql = "UPDATE goals SET leads='".$param['leads']."', sits='".$param['sits']."', appointments='".$param['appointments']."', closes='".$param['closes']."', installs='".$param['installs']."', fmunit='".$param['fmunit']."' WHERE id='".$result['id']."'";
		} else {
			$sql = "INSERT INTO goals (user_id,team,leads,sits,appointments,closes,installs,fmunit,week_start,week_end,week_number,month,created_by) VALUES ('".$param['user_id']."','".$param['team']."', '".$param['leads']."', '".$param['sits']."', '".$param['appointments']."', '".$param['closes']."', '".$param['installs']."', '".$param['fmunit']."', '".$param['week_start']."', '".$param['week_end']."', '".$param['week_number']."', '".$param['month']."', '".$param['created_by']."')";
		}		

		if ($this->conn->query($sql) === TRUE) {
			$return['success']=true;
		    return $return;
		} else {
			$return['success'] = false;
			$return['message'] = $this->conn->error;
		    return $return;
		}
		$this->conn->close();
	}

	public function get_video($category) {
		$sql = "SELECT id,name,description,link,type,tags,added_at FROM videos WHERE category='$category' ORDER BY id DESC";
		$result = $this->conn->query($sql);
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {
		        $data[] = $row;
		    }
		}
		return $data;
	}

	public function get_video_byid($id) {
		$sql = "SELECT id,name,description,link,type,tags,added_at FROM videos WHERE id='$id'";
		$result = $this->conn->query($sql);
		return $result->fetch_assoc();
	}

	public function update_video_byid($param) {
		$sql = "UPDATE videos SET name='".$param['video_name']."',description='".$param['video_description']."',link='".$param['video_link']."',tags='".$param['tags']."' WHERE id='".$param['id']."' ";
		$result = $this->conn->query($sql);
		return true;
	}

	public function delete_video($id) {
		$sql = "DELETE FROM videos WHERE id='$id'";
		if ($this->conn->query($sql) === TRUE) {
			$return['success']=true;
		    return $return;
		} else {
			$return['success'] = false;
			$return['message'] = $this->conn->error;
		    return $return;
		}	
	}

	public function save_accounts($param) {
		$sql = "SELECT * FROM account WHERE user_id='".$param['Id']."'";
		$check = $this->conn->query($sql);	
		$accounts = $check->fetch_assoc();		
		if($param['Onboarding_Complete_Percent__c']==NULL) {
			$param['Onboarding_Complete_Percent__c'] = 0;
		}
		if($param['Onboarding_Complete__c']==true) {
			$param['Onboarding_Complete__c'] = 1;
		} else {
			$param['Onboarding_Complete__c'] = 0;
		}
		if($accounts==NULL) {
			$sql = "INSERT INTO account (user_id, Name, Company_Email__c, Personal_Email__c, Position__c, Phone, Team__c, Status__c, Onboarding_Complete__c,Onboarding_Complete_Percent__c) VALUES ('".$param['Id']."','".$param['Name']."','".$param['Company_Email__c']."','".$param['Personal_Email__c']."','".$param['Position__c']."','".$param['Phone']."','".$param['Team__c']."','".$param['Status__c']."','".$param['Onboarding_Complete__c']."','".$param['Onboarding_Complete_Percent__c']."')";
			if ($this->conn->query($sql) === TRUE) {
				$return['success']=true;
			    return $return;
			} else {
				$return['success'] = false;
				$return['message'] = $this->conn->error;
			    return $return;
			}
		} else {
			$sql = "UPDATE account SET Name='".$param['Name']."',Company_Email__c='".$param['Company_Email__c']."',Personal_Email__c='".$param['Personal_Email__c']."',Position__c='".$param['Position__c']."',Phone='".$param['Phone']."',Team__c='".$param['Team__c']."',Status__c='".$param['Status__c']."',Onboarding_Complete__c='".$param['Onboarding_Complete__c']."',Onboarding_Complete_Percent__c='".$param['Onboarding_Complete_Percent__c']."' WHERE user_id='".$param['Id']."'";
			if ($this->conn->query($sql) === TRUE) {
				$return['success']=true;
			    return $return;
			} else {
				$return['success'] = false;
				$return['message'] = $this->conn->error;
			    return $return;
			}
		}
		exit;
	}

	public function get_picture($id) {
		$sql = "SELECT link FROM picture WHERE user_id='$id'";
		$result = $this->conn->query($sql);		
		return $result->fetch_assoc();
	}

	public function add_reminder($param) {	
		$sf = new horizon_salesforce();	
		$id = $param['Id'];
		$sql = "SELECT user_id,email,email_sent,CreatedDate,last_sent FROM onboarding_reminder WHERE user_id='$id'";
		$check = $this->conn->query($sql);	
		$reminders = $check->fetch_assoc();		
		if($reminders==NULL) {
			$date1=date_create($param['CreatedDate']);
			$date2=date_create(date("Y-m-d"));
			$diff=date_diff($date1,$date2);
			$day = $diff->d;
			$sent = 0;
			$last_sent = NULL;
			if($day >= 1) {
				$sf->reminder_email($param['Company_Email__c']);
				$sent=1;
				$last_sent = date("Y-m-d");
			}

			//First Time Added
			$sql = "INSERT INTO onboarding_reminder (user_id, email, email_sent, CreatedDate, last_sent) VALUES ('".$param['Id']."','".$param['Company_Email__c']."',$sent,'".$param['CreatedDate']."', '$last_sent')";
			if ($this->conn->query($sql) === TRUE) {
				$return['success']=true;
			    return $return;
			} else {
				$return['success'] = false;
				$return['message'] = $this->conn->error;
			    return $return;
			}
		} else {				
			$date1=date_create($reminders['CreatedDate']);
			$date2=date_create(date("Y-m-d"));
			$diff=date_diff($date1,$date2);
			$day = $diff->d;
			if($day >= 1) {				
				if($reminders['email_sent']==0) {
					$sf->reminder_email($reminders['email']);
					$sql = "UPDATE onboarding_reminder SET email_sent='1', last_sent='".date('Y-m-d')."' WHERE user_id='".$reminders['user_id']."'";
					if ($this->conn->query($sql) === TRUE) {
						$return['success']=true;
					    return $return;
					} else {
						$return['success'] = false;
						$return['message'] = $this->conn->error;
					    return $return;
					}
				} else if($reminders['email_sent']==1) {

					$date1=date_create($reminders['last_sent']);
					$date2=date_create(date("Y-m-d"));
					$diff=date_diff($date1,$date2);
					$day = $diff->d;

					if($day >= 1) {
						$sf->reminder_email($reminders['email']);
						$sql = "UPDATE onboarding_reminder SET email_sent='2', last_sent='".date('Y-m-d')."' WHERE user_id='".$reminders['user_id']."'";
						if ($this->conn->query($sql) === TRUE) {
							$return['success']=true;
						    return $return;
						} else {
							$return['success'] = false;
							$return['message'] = $this->conn->error;
						    return $return;
						}
					}					
				} else if($reminders['email_sent']==2) {
					$date1=date_create($reminders['last_sent']);
					$date2=date_create(date("Y-m-d"));
					$diff=date_diff($date1,$date2);
					$day = $diff->d;

					if($day >= 1) {
						$sf->reminder_email($reminders['email']);
						$sql = "UPDATE onboarding_reminder SET email_sent='3', last_sent='".date('Y-m-d')."' WHERE user_id='".$reminders['user_id']."'";
						if ($this->conn->query($sql) === TRUE) {
							$return['success']=true;
						    return $return;
						} else {
							$return['success'] = false;
							$return['message'] = $this->conn->error;
						    return $return;
						}
					}					
				} else if($reminders['email_sent']==3) {
					$return['success']="Finish";
					return $return;
				}	
			}
						
		}				
	}

	public function add_picture($id,$link) {
		$sql = "SELECT link FROM picture WHERE user_id='$id'";
		$check = $this->conn->query($sql);	
		$pic = $check->fetch_assoc();
		if($pic==NULL) {
			$sql = "INSERT INTO picture (user_id, link) VALUES ('$id','$link')";
			if ($this->conn->query($sql) === TRUE) {
				$return['success']=true;
			    return $return;
			} else {
				$return['success'] = false;
				$return['message'] = $this->conn->error;
			    return $return;
			}
		} else {									
			unlink($_SERVER['DOCUMENT_ROOT'].$pic['link']);
			$sql = "UPDATE picture SET link='$link' WHERE user_id='$id'";
			if ($this->conn->query($sql) === TRUE) {
				$return['success']=true;
			    return $return;
			} else {
				$return['success'] = false;
				$return['message'] = $this->conn->error;
			    return $return;
			}
		}		
		$this->conn->close();
	}

	public function add_picture_db($id,$link) {
		$sql = "SELECT link FROM picture WHERE user_id='$id'";
		$check = $this->conn->query($sql);	
		$pic = $check->fetch_assoc();
		if($pic==NULL) {
			$sql = "INSERT INTO picture (user_id, link) VALUES ('$id','$link')";
			if ($this->conn->query($sql) === TRUE) {
				$return['success']=true;
			    return $return;
			} else {
				$return['success'] = false;
				$return['message'] = $this->conn->error;
			    return $return;
			}
		} else {
			$return['success'] = false;
			return $return;
		}
		$this->conn->close();
	}

	public function get_user_images() {
		$sf = new horizon_salesforce();					
		$url = "SELECT Id, Name, Company_Email__c, Position__c, Status__c, Onboarding_Complete__c, profile_picture__c from Account WHERE Onboarding_Complete__c=true AND Status__c='Active'";
		return $sf->query($url);	
	}	
	public function get_user_images2() {
		$sf = new horizon_salesforce();					
		$url = "SELECT Id, Name, Company_Email__c, Position__c, Status__c, Onboarding_Complete__c, profile_picture__c from Account WHERE Onboarding_Complete__c=false AND Status__c='Onboarding'";
		return $sf->query($url);	
	}	

	public function truncate_table($table_name) {
		$sql = "TRUNCATE TABLE $table_name";
		$result = $this->conn->query($sql);
		return $result;
	}	

	public function update_opportunity($type) {
		$x=0;
		if($type=="leads") {
			$url = "SELECT Name, Account__c, Field_Marketer__c, Spotio_Status__c, Proposal_Requested_Date_Time__c FROM Opportunity WHERE Spotio_Status__c!='Not Interested' AND Spotio_Status__c!='Not Home' AND Spotio_Status__c!='Go Back' AND Proposal_Requested_Date_Time__c != NULL";
		} else if($type=="sits") {			
			$url = "SELECT Name, Account__c, Field_Marketer__c, Sat__c, Appointment_Date__c FROM Opportunity WHERE Appointment_Date__c !=NULL";
		} else {
			exit;
		}	

		$sf = new horizon_salesforce();	
		$result = $sf->query($url);						 
		$records = $result['records'];
		$result['records'] = $records;
		if(isset($result['nextRecordsUrl'])) {
			$x=1;
		}
		while($x==1) {
			$result = $sf->get(SALESFORCE_INSTANCE_URL.$result['nextRecordsUrl']);
			$records = array_merge($records, $result['records']);
			if(isset($result['nextRecordsUrl'])) {
				$x=1;
			} else {
				$x=0;
			}
		}		
		
		$count = 0;
		foreach ($records as $key => $value) {
			$data['url'] = $value['attributes']['url'];
			$account = $this->get_account_byname($value['Field_Marketer__c']);			
			$data['account_id'] = $value['Account__c'];
			$data['Name'] = NULL; //change to $value['Name'] for checking
			$data['Field_Marketer__c'] = $account['user_id'];					
			if($type=="leads") {			
				$data['Spotio_Status__c'] = $value['Spotio_Status__c'];
				$data['Proposal_Requested_Date_Time__c'] = date("Y-m-d",strtotime($value['Proposal_Requested_Date_Time__c']));				
				$result = $this->add_opportunity($data,$type);
			} else if($type=="sits") {
				if($value['Sat__c']) {
					$sitval = 1;
				} else {
					$sitval = 0;
				}
				$data['Sat__c'] = $sitval;				
				$data['Appointment_Date__c'] = $value['Appointment_Date__c'];				
				$this->add_opportunity($data,$type);
			}
			$count++;
		}		
		
		echo ucwords($type)." Updated: ".$count."<br>";
		return true;
	}

	public function add_opportunity($param,$type) {
		$sql = "SELECT url FROM opportunity WHERE url='".$param['url']."'";	
		$check = $this->conn->query($sql);	
		$opp = $check->fetch_assoc();
		if($opp==NULL) {
			if($type=="leads") {
				$sql = "INSERT INTO opportunity (account_id, Name, Field_Marketer__c, Spotio_Status__c, Proposal_Requested_Date_Time__c,url) VALUES ('".$param['account_id']."','".$param['Name']."','".$param['Field_Marketer__c']."','".$param['Spotio_Status__c']."','".$param['Proposal_Requested_Date_Time__c']."','".$param['url']."')";
			} else if($type=="sits") {
				$sql = "INSERT INTO opportunity (account_id, Name, Field_Marketer__c, Sat__c, Appointment_Date__c, url) VALUES ('".$param['account_id']."','".$param['Name']."','".$param['Field_Marketer__c']."','".$param['Sat__c']."','".$param['Appointment_Date__c']."','".$param['url']."')";
			} else {
				exit;
			}				
			if ($this->conn->query($sql) === TRUE) {
				$return['success']=true;
			    return $return;
			} else {
				$return['success'] = false;
				$return['message'] = $this->conn->error;
			    return $return;
			}
		} else {	
			if($type=="leads") {
				$sql = "UPDATE opportunity SET account_id='".$param['account_id']."',Name='".$param['Name']."',Field_Marketer__c='".$param['Field_Marketer__c']."',Spotio_Status__c='".$param['Spotio_Status__c']."',Proposal_Requested_Date_Time__c='".$param['Proposal_Requested_Date_Time__c']."' WHERE url='".$param['url']."'";
			} else if($type=="sits") {
				$sql = "UPDATE opportunity SET account_id='".$param['account_id']."',Name='".$param['Name']."',Field_Marketer__c='".$param['Field_Marketer__c']."',Sat__c='".$param['Sat__c']."',Appointment_Date__c='".$param['Appointment_Date__c']."' WHERE url='".$param['url']."'";
			} else {
				exit;
			}	
			
			if ($this->conn->query($sql) === TRUE) {
				$return['success']=true;
			    return $return;
			} else {
				$return['success'] = false;
				$return['message'] = $this->conn->error;
			    return $return;
			}
		}
	}

	public function update_residential_projects($type) {
		$x=0;
		if($type=="closes") {
			$url = "SELECT Field_Marketer__c, Site_Audit_Scheduled_Date_Time__c, Account__c FROM Residential_Projects__c WHERE Site_Audit_Scheduled_Date_Time__c != NULL";
		} else if($type=="installs") {
			$url = "SELECT Account__c, Install_Complete_Date_Time__c, Field_Marketer__c FROM Residential_Projects__c WHERE Install_Complete_Date_Time__c != NULL";
		} else if($type=="commission") {
			$url = "SELECT Account__c, Earned_Commission__c, Earned_Commission_Paid_Date__c, Field_Marketer__c FROM Residential_Projects__c WHERE Earned_Commission_Paid_Date__c != NULL";
		} else if($type=="kw") {
			$url = "SELECT Account__c, Final_System_Size__c, Install_Complete_Date_Time__c, Field_Marketer__c FROM Residential_Projects__c WHERE Final_System_Size__c != NULL AND Install_Complete_Date_Time__c != NULL";
		} else {
			exit;
		}
		$sf = new horizon_salesforce();	
		$result = $sf->query($url);						 
		$records = $result['records'];
		$result['records'] = $records;
		if(isset($result['nextRecordsUrl'])) {
			$x=1;
		}
		while($x==1) {
			$result = $sf->get(SALESFORCE_INSTANCE_URL.$result['nextRecordsUrl']);
			$records = array_merge($records, $result['records']);
			if(isset($result['nextRecordsUrl'])) {
				$x=1;
			} else {
				$x=0;
			}
		}		

		$count = 0;
		foreach ($records as $key => $value) {
			$data['url'] = $value['attributes']['url'];
			$data['account_id'] = $value['Account__c'];
			$account = $this->get_account_byname($value['Field_Marketer__c']);			
			$data['Field_Marketer__c'] = $account['user_id'];

			if($type=="closes") {							
				$data['Site_Audit_Scheduled_Date_Time__c'] = date("Y-m-d",strtotime($value['Site_Audit_Scheduled_Date_Time__c']));				
				$return = $this->add_residential_projects($data,$type);
			} else if($type=="installs") {								
				$data['Install_Complete_Date_Time__c'] = date("Y-m-d",strtotime($value['Install_Complete_Date_Time__c']));				
				$return = $this->add_residential_projects($data,$type);
			} else if($type=="commission") {				
				$data['Earned_Commission__c'] = $value['Earned_Commission__c'];
				$data['Earned_Commission_Paid_Date__c'] = $value['Earned_Commission_Paid_Date__c'];
				$return = $this->add_residential_projects($data,$type);
			} else if($type=="kw") {				
				$data['Final_System_Size__c'] = $value['Final_System_Size__c'];
				$data['Install_Complete_Date_Time__c'] = date("Y-m-d",strtotime($value['Install_Complete_Date_Time__c']));							
				$return = $this->add_residential_projects($data,$type);
			} else {
				exit;
			}
			$count++;
		}		
		
		echo ucwords($type)." Updated: ".$count."<br>";		
		return $return;
	}

	public function add_residential_projects($param,$type) {
		$sql = "SELECT url FROM residential_projects WHERE url='".$param['url']."'";	
		$check = $this->conn->query($sql);	
		$opp = $check->fetch_assoc();		
		if($opp==NULL) {
			if($type=="closes") {
				$sql = "INSERT INTO residential_projects (account_id, Site_Audit_Scheduled_Date_Time__c, Field_Marketer__c, url) VALUES ('".$param['account_id']."','".$param['Site_Audit_Scheduled_Date_Time__c']."','".$param['Field_Marketer__c']."','".$param['url']."')";
			} else if($type=="installs") {
				$sql = "INSERT INTO residential_projects (account_id, Install_Complete_Date_Time__c, Field_Marketer__c, url) VALUES ('".$param['account_id']."','".$param['Install_Complete_Date_Time__c']."','".$param['Field_Marketer__c']."','".$param['url']."')";
			} else if($type=="commission") {
				$sql = "INSERT INTO residential_projects (account_id, Earned_Commission__c, Earned_Commission_Paid_Date__c, url) VALUES ('".$param['account_id']."','".$param['Earned_Commission__c']."','".$param['Earned_Commission_Paid_Date__c']."','".$param['url']."')";
			} else if($type=="kw") {
				$sql = "INSERT INTO residential_projects (account_id, Final_System_Size__c, Install_Complete_Date_Time__c, url) VALUES ('".$param['account_id']."','".$param['Final_System_Size__c']."','".$param['Install_Complete_Date_Time__c']."','".$param['url']."')";
			} else {
				exit;
			}

			if ($this->conn->query($sql) === TRUE) {
				$return['success']=true;
			    return $return;
			} else {
				$return['success'] = false;
				$return['message'] = $this->conn->error;
			    return $return;
			}
		} else {	
			if($type=="closes") {
				$sql = "UPDATE residential_projects SET account_id='".$param['account_id']."',Site_Audit_Scheduled_Date_Time__c='".$param['Site_Audit_Scheduled_Date_Time__c']."',Field_Marketer__c='".$param['Field_Marketer__c']."' WHERE url='".$param['url']."'";
			} else if($type=="installs") {
				$sql = "UPDATE residential_projects SET account_id='".$param['account_id']."',Install_Complete_Date_Time__c='".$param['Install_Complete_Date_Time__c']."',Field_Marketer__c='".$param['Field_Marketer__c']."' WHERE url='".$param['url']."'";
			} else if($type=="commission") {
				$sql = "UPDATE residential_projects SET account_id='".$param['account_id']."',Earned_Commission__c='".$param['Earned_Commission__c']."',Earned_Commission_Paid_Date__c='".$param['Earned_Commission_Paid_Date__c']."' WHERE url='".$param['url']."'";
			} else if($type=="kw") {
				$sql = "UPDATE residential_projects SET Final_System_Size__c='".$param['Final_System_Size__c']."' WHERE url='".$param['url']."'";
			} else {
				exit;
			}											
			
			if ($this->conn->query($sql) === TRUE) {
				$return['success']=true;
			    return $return;
			} else {
				$return['success'] = false;
				$return['message'] = $this->conn->error;
			    return $return;
			}
		}
	}

	public function get_fmunit_leads($account_id,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period("Custom",$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];
		

		$sql = "SELECT account_id, Field_Marketer__c, Spotio_Status__c, Proposal_Requested_Date_Time__c FROM opportunity WHERE account_id = '$account_id' AND Field_Marketer__c != '$account_id' AND Spotio_Status__c='Lead' AND Proposal_Requested_Date_Time__c >= '$prevdate' AND Proposal_Requested_Date_Time__c <= '$curdate'";	
		$result = $this->conn->query($sql);	
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {		    	
		        $data[] = $row;
		    }
		}		
		return count($data);
	}
	public function get_fmunit_sits($account_id,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period("Custom",$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];
		

		$sql = "SELECT account_id, Field_Marketer__c, Sat__c, Appointment_Date__c FROM opportunity WHERE account_id = '$account_id' AND Field_Marketer__c != '$account_id' AND Field_Marketer__c != '' AND Appointment_Date__c >= '$prevdate' AND Appointment_Date__c <= '$curdate' AND Sat__c = 1";	
		$result = $this->conn->query($sql);	
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {		    	
		        $data[] = $row;
		    }
		}		
		return count($data);
	}

	public function get_lead($account_id,$period,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];
		
		$sql = "SELECT Spotio_Status__c, Proposal_Requested_Date_Time__c FROM opportunity WHERE Field_Marketer__c = '$account_id' AND Spotio_Status__c!='Not Interested' AND Spotio_Status__c!='Go Back' AND  Proposal_Requested_Date_Time__c >= '$prevdate' AND Proposal_Requested_Date_Time__c <= '$curdate'";		
		$result = $this->conn->query($sql);	
		if($result) {
			$data=[];
			if ($result->num_rows > 0) {		    
			    while($row = $result->fetch_assoc()) {		    	
			        $data[] = $row;
			    }
			}		
			return count($data);	
		} else {
			return 0;
		}
		
	}

	public function get_lead_fmunit($account_id,$period,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];

		$account = $this->get_account($account_id);

		if($account['Position__c']=="Sr Energy Consultant" || $account['Position__c']=="Manager" || $account['Position__c']=="VP" || $account['Position__c']=="CEO") {
			$sql = "SELECT Spotio_Status__c, Proposal_Requested_Date_Time__c FROM opportunity WHERE (account_id != Field_Marketer__c) AND account_id = '$account_id' AND Spotio_Status__c!='Not Interested' AND Spotio_Status__c!='Go Back' AND  Proposal_Requested_Date_Time__c >= '$prevdate' AND Proposal_Requested_Date_Time__c <= '$curdate'";				
			$result = $this->conn->query($sql);	
			if($result) {
				$data=[];
				if ($result->num_rows > 0) {		    
				    while($row = $result->fetch_assoc()) {		    	
				        $data[] = $row;
				    }
				}		
				return count($data);	
			} else {
				return 0;
			}
		} else {
			return 0;
		}				
	}

	public function get_sit_fmunit($account_id,$period,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];

		$account = $this->get_account($account_id);

		if($account['Position__c']=="Sr Energy Consultant" || $account['Position__c']=="Manager" || $account['Position__c']=="VP" || $account['Position__c']=="CEO") {
			$sql = "SELECT Spotio_Status__c, Appointment_Date__c FROM opportunity WHERE (account_id != Field_Marketer__c) AND account_id = '$account_id' AND Spotio_Status__c!='Not Interested' AND Spotio_Status__c!='Go Back' AND  Appointment_Date__c >= '$prevdate' AND Appointment_Date__c <= '$curdate' AND Sat__c = 1";				
			$result = $this->conn->query($sql);	
			if($result) {
				$data=[];
				if ($result->num_rows > 0) {		    
				    while($row = $result->fetch_assoc()) {		    	
				        $data[] = $row;
				    }
				}		
				return count($data);	
			} else {
				return 0;
			}
		} else {
			return 0;
		}				
	}

	public function get_commission($account_id,$period,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];
		
		$sql = "SELECT Earned_Commission__c, Earned_Commission_Paid_Date__c, account_id FROM residential_projects WHERE account_id = '$account_id' AND Earned_Commission_Paid_Date__c >= '$prevdate' AND Earned_Commission_Paid_Date__c <= '$curdate'";
		$result = $this->conn->query($sql);	
		$commission = 0;
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {
		        $commission += $row['Earned_Commission__c'];
		    }
		}		
		return number_format($commission);
	}

	public function get_closes($account_id,$period,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];
		
		$sql = "SELECT Site_Audit_Scheduled_Date_Time__c FROM residential_projects WHERE account_id = '$account_id' AND Site_Audit_Scheduled_Date_Time__c >= '$prevdate' AND Site_Audit_Scheduled_Date_Time__c <= '$curdate'";
		$result = $this->conn->query($sql);	
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {
		        $data[] = $row;
		    }
		}		
		return count($data);
	}

	public function get_assisted_close($account_id,$period,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];
		$account = $this->get_account($account_id);

		if($account['Position__c']=="Field Marketer" || $account['Position__c']=="Field Marketer Elite") {
			$sql = "SELECT Site_Audit_Scheduled_Date_Time__c FROM residential_projects WHERE Field_Marketer__c = '$account_id' AND Site_Audit_Scheduled_Date_Time__c >= '$prevdate' AND Site_Audit_Scheduled_Date_Time__c <= '$curdate'";
			$result = $this->conn->query($sql);	
			$data=[];
			if ($result->num_rows > 0) {		    
			    while($row = $result->fetch_assoc()) {
			        $data[] = $row;
			    }
			}		
			return count($data);
		} else {
			return 0;
		}
	}

	public function get_sits($account_id,$period,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];

		$account = $this->get_account($account_id);

		if($account['Position__c']=="Field Marketer" || $account['Position__c']=="Field Marketer Elite") {
			$sql = "SELECT Sat__c, Appointment_Date__c FROM opportunity WHERE Field_Marketer__c = '$account_id' AND Appointment_Date__c >= '$prevdate' AND Appointment_Date__c <= '$curdate' AND Sat__c = 1";
		} else {
			$sql = "SELECT Sat__c, Appointment_Date__c FROM opportunity WHERE account_id = '$account_id' AND Appointment_Date__c >= '$prevdate' AND Appointment_Date__c <= '$curdate' AND Sat__c = 1";
		}
		
		
		$result = $this->conn->query($sql);	
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {
		        $data[] = $row;
		    }
		}		
		return count($data);
	}

	public function get_appointments($account_id,$period,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];

		$account = $this->get_account($account_id);

		$sql = "SELECT Sat__c, Appointment_Date__c FROM opportunity WHERE Field_Marketer__c = '$account_id' AND Appointment_Date__c >= '$prevdate' AND Appointment_Date__c <= '$curdate'";
		// if($account['Position__c']=="Field Marketer" || $account['Position__c']=="Field Marketer Elite") {
		// 	$sql = "SELECT Sat__c, Appointment_Date__c FROM opportunity WHERE Field_Marketer__c = '$account_id' AND Appointment_Date__c >= '$prevdate' AND Appointment_Date__c <= '$curdate'";
		// } else {
		// 	$sql = "SELECT Sat__c, Appointment_Date__c FROM opportunity WHERE account_id = '$account_id' AND Appointment_Date__c >= '$prevdate' AND Appointment_Date__c <= '$curdate'";
		// }
		
		$result = $this->conn->query($sql);	
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {
		        $data[] = $row;
		    }
		}		
		return count($data);
	}

	public function get_team_sits($period,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];

		$sql = "SELECT account_id,Field_Marketer__c,Sat__c, Appointment_Date__c FROM opportunity WHERE Appointment_Date__c >= '$prevdate' AND Appointment_Date__c <= '$curdate' AND Sat__c = 1";	
		
		$result = $this->conn->query($sql);	
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {
		        $data[] = $row;
		    }
		}		
		return $data;
	}

	public function get_team_appointments($period,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];

		$sql = "SELECT account_id,Field_Marketer__c,Sat__c, Appointment_Date__c FROM opportunity WHERE Appointment_Date__c >= '$prevdate' AND Appointment_Date__c <= '$curdate'";	
		
		$result = $this->conn->query($sql);	
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {
		        $data[] = $row;
		    }
		}		
		return $data;
	}

	public function get_installs($account_id,$period,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];
		
		$sql = "SELECT Install_Complete_Date_Time__c FROM residential_projects WHERE account_id = '$account_id' AND Install_Complete_Date_Time__c >= '$prevdate' AND Install_Complete_Date_Time__c <= '$curdate'";
		$result = $this->conn->query($sql);	
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {
		        $data[] = $row;
		    }
		}		
		return count($data);
	}

	public function get_assisted_installs($account_id,$period,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];

		$sql = "SELECT Install_Complete_Date_Time__c FROM residential_projects WHERE (account_id != Field_Marketer__c) AND account_id = '$account_id' AND Install_Complete_Date_Time__c >= '$prevdate' AND Install_Complete_Date_Time__c <= '$curdate'";
		
		$result = $this->conn->query($sql);	
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {
		        $data[] = $row;
		    }
		}		
		return count($data);
	}

	public function get_assisted_installs_fm($account_id,$period,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];
		$account = $this->get_account($account_id);

		if($account['Position__c']=="Field Marketer" || $account['Position__c']=="Field Marketer Elite") {
			$sql = "SELECT Install_Complete_Date_Time__c FROM residential_projects WHERE (account_id != Field_Marketer__c) AND Field_Marketer__c = '$account_id' AND Install_Complete_Date_Time__c >= '$prevdate' AND Install_Complete_Date_Time__c <= '$curdate'";
		
			$result = $this->conn->query($sql);	
			$data=[];
			if ($result->num_rows > 0) {		    
			    while($row = $result->fetch_assoc()) {
			        $data[] = $row;
			    }
			}		
			return count($data);	
		} else {
			return 0;
		}
		
	}

	public function get_self_generated_installs($account_id,$period,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];
		
		$sql = "SELECT Install_Complete_Date_Time__c FROM residential_projects WHERE account_id = '$account_id' AND Field_Marketer__c = '$account_id' AND Install_Complete_Date_Time__c >= '$prevdate' AND Install_Complete_Date_Time__c <= '$curdate'";
		$result = $this->conn->query($sql);	
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {
		        $data[] = $row;
		    }
		}		
		return count($data);
	}	

	public function get_KW_per_year($account_id) {
		$prevdate = date("Y")."-01-01T00:00:00.000+0000";		
		$curdate = date("Y-m-d")."T00:00:00.000+0000";
		
		$sql = "SELECT Final_System_Size__c FROM residential_projects WHERE account_id = '$account_id' AND Install_Complete_Date_Time__c >= '$prevdate' AND Install_Complete_Date_Time__c <= '$curdate'";		
		$result = $this->conn->query($sql);					
		$kw=0;
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {
		        $kw += $row['Final_System_Size__c'];
		    }
		}
		return number_format($kw,2);
	}	
	

	public function delete_account($id) {		
		$sql = "DELETE FROM account WHERE user_id = '$id'";
		$this->conn->query($sql);	
	}

	public function get_pwrstation_accounts($column,$sort,$search=null) {
		if($column == "Email") {
			$column = "Company_Email__c";
		} else if($column == "Team") {
			$column = "Team__c";
		} else if($column == "Role") {
			$column = "Position__c";
		}
		if($search!=null) {
			$sql = "SELECT user_id, Name, Company_Email__c, Position__c, Status__c, Team__c, Onboarding_Complete__c FROM account WHERE (Name LIKE '%$search%' OR Company_Email__c LIKE '%$search%') AND Position__c!='Admin' AND Status__c='Active' AND is_hidden=0 ORDER BY ".$column." ".$sort;						
		} else {
			$sql = "SELECT user_id, Name, Company_Email__c, Position__c, Status__c, Team__c, Onboarding_Complete__c FROM account WHERE Position__c!='Admin' AND Status__c='Active' AND is_hidden=0 ORDER BY ".$column." ".$sort;
		}
		
		$result = $this->conn->query($sql);			
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {
		    	$row['Id'] = $row['user_id'];
		    	$picture = $this->get_picture($row['user_id']);
				if($picture!=NULL) {
					$row['picture'] = IMAGE_BASE_URL.$picture['link'];
				} else {
					$row['picture'] = "/pic/";
				}
		        $data[] = $row;
		    }
		}				
		return $data;
	}

	public function get_onboarding_accounts($column,$sort,$search=null) {
		if($column == "Email") {
			$column = "Company_Email__c";
		} else if($column == "Team") {
			$column = "Team__c";
		} else if($column == "Role") {
			$column = "Position__c";
		} else if($column == "% Complete") {
			$column = "Onboarding_Complete_Percent__c";
		}

		if($search!=null) { 
			$sql = "SELECT user_id, Name, Company_Email__c, Position__c, Status__c, Onboarding_Complete__c, Onboarding_Complete_Percent__c from account WHERE (Name LIKE '%$search%' OR Company_Email__c LIKE '%$search%') AND Status__c='Onboarding' AND is_hidden=0 ORDER BY ".$column." ".$sort;
		} else {
			$sql = "SELECT user_id, Name, Company_Email__c, Position__c, Status__c, Onboarding_Complete__c, Onboarding_Complete_Percent__c from account WHERE  Status__c='Onboarding' AND is_hidden=0 ORDER BY ".$column." ".$sort;
		}
		
		$result = $this->conn->query($sql);	
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {
		    	$row['Id'] = $row['user_id'];
		    	$picture = $this->get_picture($row['user_id']);
				if($picture!=NULL) {
					$row['picture'] = IMAGE_BASE_URL.$picture['link'];
				} else {
					$row['picture'] = "/pic/";
				}
		        $data[] = $row;
		    }
		}				
		return $data;
	}

	public function add_team_names($name) {
		$sql = "INSERT INTO teams (name) VALUES ('$name')";
		if ($this->conn->query($sql) === TRUE) {
			$return['success']=true;
		    return $return;
		} else {
			$return['success'] = false;
			$return['message'] = $this->conn->error;
		    return $return;
		}
	}

	public function get_team_names() {
		$sql = "SELECT * from teams ORDER BY name ASC";
		$result = $this->conn->query($sql);	
		$data=[];
		if($result!=NULL) {
			if ($result->num_rows > 0) {		    
			    while($row = $result->fetch_assoc()) {
			        $data[] = $row;
			    }
			}
		}
		
		return $data;
	}

	public function get_fm() {
		$sql = "SELECT user_id, Name, Company_Email__c, Position__c, Status__c, Team__c from account WHERE user_id !='".$_SESSION['user_id']."' AND  Status__c='Active' AND (Position__c='Field Marketer' OR Position__c='Field Marketer Elite') AND is_hidden=0";
		$result = $this->conn->query($sql);	
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {
		    	$row['Id'] = $row['user_id'];
		    	$picture = $this->get_picture($row['user_id']);
				if($picture!=NULL) {
					$row['picture'] = IMAGE_BASE_URL.$picture['link'];
				} else {
					$row['picture'] = "/pic/";
				}
		        $data[] = $row;
		    }
		}				
		return $data;
	}

	public function get_ec() {
		$sql = "SELECT user_id, Name, Company_Email__c, Position__c, Status__c, Team__c from account WHERE user_id !='".$_SESSION['user_id']."' AND  Status__c='Active' AND (Position__c='Jr Energy Consultant' OR Position__c='Sr Energy Consultant' OR Position__c='VP' OR Position__c='CEO' OR Position__c='Manager') AND is_hidden=0";
		$result = $this->conn->query($sql);	
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {
		    	$row['Id'] = $row['user_id'];
		    	$picture = $this->get_picture($row['user_id']);
				if($picture!=NULL) {
					$row['picture'] = IMAGE_BASE_URL.$picture['link'];
				} else {
					$row['picture'] = "/pic/";
				}
		        $data[] = $row;
		    }
		}				
		return $data;
	}

	public function get_user_basicinfo($id) {		
		$sql = "SELECT user_id, Name, Company_Email__c, Position__c, Status__c, Team__c from account WHERE user_id='$id'";
		$result = $this->conn->query($sql);	
		$data = $result->fetch_assoc();
		$picture = $this->get_picture($id);
		if($picture!=NULL) {
			$pic = IMAGE_BASE_URL.$picture['link'];
		} else {
			$pic = "/pic/";
		}
		$data['picture']=IMAGE_BASE_URL.$pic;
		return $data;
	}

	public function get_goals($team,$week_number=null,$month=null) {
		if($month!=null)
		{
			$sql = "SELECT leads,sits,closes,installs,user_id from goals WHERE team='$team' AND month='$month'";
		} else {
			$sql = "SELECT leads,sits,closes,installs,user_id from goals WHERE team='$team' AND week_number='$week_number'";
		}
		$result = $this->conn->query($sql);	
		if($result) {
			$data = $result->fetch_assoc();	
			return $data;
		} else {
			return false;
		}		
	}

	public function get_goals_userid_period($user_id,$week_number=null,$month=null) {
		if($month!=null)
		{
			$sql = "SELECT id,leads,sits,appointments,closes,installs,fmunit from goals WHERE user_id='$user_id' AND month='$month'";
		} else {
			$sql = "SELECT id,leads,sits,appointments,closes,installs,fmunit from goals WHERE user_id='$user_id' AND week_number='$week_number'";
		}
		$result = $this->conn->query($sql);	
		if($result) {
			$data = $result->fetch_assoc();	
			return $data;
		} else {
			return false;
		}	
	}

	public function get_all_users() {
		$sql = "SELECT * FROM account WHERE Position__c != 'Admin' AND is_hidden=0";
		$result = $this->conn->query($sql);	
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {		    	
		        $data[] = $row;
		    }
		}				
		return $data;
	}

	public function get_company_leads($period,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];
		
		$sql = "SELECT account_id, Spotio_Status__c, Proposal_Requested_Date_Time__c FROM opportunity WHERE Spotio_Status__c!='Not Interested' AND Spotio_Status__c!='Not Home' AND Spotio_Status__c!='Go Back' AND Proposal_Requested_Date_Time__c >= '$prevdate' AND Proposal_Requested_Date_Time__c <= '$curdate'";		
		$result = $this->conn->query($sql);	
		if($result) {
			$data=[];
			if ($result->num_rows > 0) {		    
			    while($row = $result->fetch_assoc()) {		    	
			        $data[] = $row;
			    }
			}		
			return count($data);	
		} else {
			return 0;
		}		
	}

	public function get_company_sits($period,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];
		
		$sql = "SELECT account_id, Sat__c, Appointment_Date__c FROM opportunity WHERE Appointment_Date__c >= '$prevdate' AND Appointment_Date__c <= '$curdate' AND Sat__c = 1";
		$result = $this->conn->query($sql);	
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {
		        $data[] = $row;
		    }
		}		
		return count($data);
	}

	public function get_company_appointments($period,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];
		
		$sql = "SELECT account_id, Sat__c, Appointment_Date__c FROM opportunity WHERE Appointment_Date__c >= '$prevdate' AND Appointment_Date__c <= '$curdate'";
		$result = $this->conn->query($sql);	
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {
		        $data[] = $row;
		    }
		}		
		return count($data);
	}

	public function get_company_closes($period,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];		
		$sql = "SELECT account_id, Site_Audit_Scheduled_Date_Time__c FROM residential_projects WHERE Site_Audit_Scheduled_Date_Time__c >= '$prevdate' AND Site_Audit_Scheduled_Date_Time__c <= '$curdate'";
		$result = $this->conn->query($sql);	
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {
		        $data[] = $row;
		    }
		}		
		return count($data);
	}

	public function get_company_installs($period,$customprev=false,$customcur=false) {
		$sf = new horizon_salesforce();
		$date = $sf->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];		
		$sql = "SELECT account_id, Install_Complete_Date_Time__c FROM residential_projects WHERE Install_Complete_Date_Time__c >= '$prevdate' AND Install_Complete_Date_Time__c <= '$curdate'";
		$result = $this->conn->query($sql);	
		$data=[];
		if ($result->num_rows > 0) {		    
		    while($row = $result->fetch_assoc()) {
		        $data[] = $row;
		    }
		}		
		return count($data);
	}
}