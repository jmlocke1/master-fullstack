import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
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
    public status: string = '';
    public token:string = '';
    public identity:any ;
    constructor(
      private _userService: UserService,
      private _router: Router,
      private _route: ActivatedRoute
    ) { 
        this.page_title = 'Identifícate';
        this.user = new User();
    }

    ngOnInit(): void {
      // Se ejecuta simepre y cierra sesión solo cuando le llega el parámetro por la url
      this.logout();
    }

    onSubmit(form: any){
      this._userService.signup(this.user, "true").subscribe(
        response => {
          console.log("Respuesta del servidor:");
          console.log(response);
          if(response.status === 'success'){
            this.status = 'success';
            this.token = response.token;
            this.identity = response.user;
            localStorage.setItem('token', this.token);
            localStorage.setItem('identity', JSON.stringify(this.identity));
            // Redirección a inicio
            this._router.navigateByUrl('/inicio');
          }
        },
        error => {
          this.status = 'error';
          console.log(<any>error);
        }
      );
    }

    logout(){
      this._route.params.subscribe( params => {
        let logout = +params['sure'];

        if(logout == 1){
          localStorage.removeItem('identity');
          localStorage.removeItem('token');
          this.identity = null;
          this.token = '';

          // Redirección a inicio
          this._router.navigateByUrl('/inicio');
          //location.reload();
        }
      });
    }

}
