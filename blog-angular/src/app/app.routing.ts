// Imports necesarios
import { ModuleWithProviders } from "@angular/compiler/src/core";
import { Routes, RouterModule } from "@angular/router";


// Importar componentes
import { LoginComponent } from "./components/login/login.component";
import { RegisterComponent } from "./components/register/register.component";
import { HomeComponent } from './components/home/home.component';
import { ErrorComponent } from './components/error/error.component';

// Definir las rutas
const appRoutes: Routes = [
	{path: '', component: LoginComponent},
	{path: 'inicio', component: LoginComponent},
	{path: 'login', component: LoginComponent},
	{path: 'registro', component: RegisterComponent},
	{path: '**', component: ErrorComponent}
];

// Exportar configuración
export const appRoutingProviders: any[] = [];
export const routing: ModuleWithProviders = RouterModule.forRoot(appRoutes);