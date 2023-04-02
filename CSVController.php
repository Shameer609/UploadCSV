
    public function uploadLeadCSV(Request $request)
    {
        // dd($request->file('csv')->extension());
        try {
            if ($request->file('csv')->extension() == 'txt') {

                ini_set('max_execution_time', 1180);
                $filename = '';
                if ($file = $request->file('csv')) {
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $file->move('public/uploads/temp/', $filename);
                }
                $datas = "";
                $file = fopen(public_path('public/uploads/temp/' . $filename), "r");

                $i = 1;
                $s = 1;
                $header = fgetcsv($file, 1000, ",");
                $name = $this->CsvGetColumnIndex('Name', $header);
                $phone = $this->CsvGetColumnIndex('Phone', $header);
                $city = $this->CsvGetColumnIndex('City', $header);
                $email = $this->CsvGetColumnIndex('Email', $header);
                // $amount = $this->CsvGetColumnIndex('Amount', $header);

                while (($line = fgetcsv($file)) !== FALSE) {
                    try {
                        DB::beginTransaction();
                        $business_id = $request->session()->get('user.business_id');
                        $Contact = new Contact;
                        $Contact->type = 'lead';
                        $Contact->business_id = $business_id;
                        $Contact->supplier_business_name = $line[$name];
                        $Contact->name = $line[$name];
                        $Contact->mobile = (empty($line[$phone]) ? '' : $line[$phone]);
                        $Contact->city = $line[$city];
                        $Contact->email = $line[$email];
                        $Contact->created_by = Auth::user()->id;
                        // $Contact->custom_field1 = $line[$amount];
                        $ref_count = $this->setAndGetReferenceCount('contacts', $business_id);
                        $contact_id = $this->generateReferenceNumber('contacts', $ref_count, $business_id);
                        $Contact->contact_id = $contact_id;
                        $Contact->save();
                        DB::table('crm_lead_users')->insert(['contact_id' => $Contact->id, 'user_id' => $Contact->created_by]);

                        $i++;
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        return $e->getMessage();
                    }
                }

                fclose($file);
                if (file_exists('public/uploads/temp/' . $filename)) {
                    unlink('public/uploads/temp/' . $filename);
                }
                return back()->with('msg','success');
            }else{
                return back()->with('msg','format');
            }
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            return back()->with('msg','failed');
        }
    }

    

    public function CsvGetColumnIndex($value,$header)
    {
        $key=  array_search($value,$header);
        if(isset($key)&&$key+1!=false){
            return $key;
        }
        else {
                $this->log.="<br> Column '".$value."' not Found in First row ";
        }
    }
