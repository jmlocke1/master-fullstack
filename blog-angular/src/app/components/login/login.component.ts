import { Component, OnInit } from '@angular/core';
import { User } from 'src/app/models/user';
import { UserService } from 'src/app/services/user.service';

@Component({
  selector: 'login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
  providers: [UserService]
})
export class LoginComponent implements OnInit {
    public page_title: string;
    public user: User;
    public status: string;
    constructor(
      private _userService: UserService
    ) { 
        this.page_title = 'Identifícate';
        this.user = new User(1, '', '', 'ROLE_USER', '', '', '','');
        this.status = '';
    }

    ngOnInit(): void {
    }

    onSubmit(form: any){
      this._userService.signup(this.user).subscribe(
        response => {
          console.log(response);
        },
        error => {
          this.status = 'error';
          console.log(<any>error);
        }
      );
    }

}
